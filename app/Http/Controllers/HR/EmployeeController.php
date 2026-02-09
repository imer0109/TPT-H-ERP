<?php

namespace App\Http\Controllers\HR;

use App\Models\Employee;
use App\Models\Company;
use App\Models\Agency;
use App\Models\Warehouse;
use App\Models\Position;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
     public function index(Request $request)
    {
        $employees = Employee::with(['currentCompany', 'currentAgency', 'currentWarehouse', 'currentPosition', 'supervisor'])
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('matricule', 'like', "%{$search}%")
                      ->orWhere('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->company_id, function($query, $company_id) {
                $query->where('company_id', $company_id);
            })
            ->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $companies = Company::pluck('raison_sociale', 'id');
        $agencies = Agency::pluck('nom', 'id');
        $warehouses = Warehouse::pluck('nom', 'id');
        $positions = Position::all();
        $supervisors = Employee::join('positions', 'employees.current_position_id', '=', 'positions.id')
            ->where('positions.is_management', true)
            ->select('employees.*')
            ->get();

        return view('employees.create', compact(
            'companies',
            'agencies',
            'warehouses',
            'positions',
            'supervisors'
        ));
    }

    public function store(Request $request)
    {
        if (($validator = EmployeeRequest::validate())->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        Employee::create($data);

        return redirect()
            ->route('hr.employees.index')
            ->with('success', 'Employé créé avec succès');
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'currentCompany',
            'currentAgency',
            'currentWarehouse',
            'currentPosition',
            'supervisor',
            'contracts',
            'leaves',
            'attendances',
            'payslips',
            'evaluations'
        ]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $companies = Company::pluck('raison_sociale', 'id');
        $agencies = Agency::pluck('nom', 'id');
        $warehouses = Warehouse::pluck('nom', 'id');
        $positions = Position::all();
        $supervisors = Employee::join('positions', 'employees.current_position_id', '=', 'positions.id')
            ->where('positions.is_management', true)
            ->where('employees.id', '!=', $employee->id)
            ->select('employees.*')
            ->get();

        return view('employees.edit', compact(
            'employee',
            'companies',
            'agencies',
            'warehouses',
            'positions',
            'supervisors'
        ));
    }

    public function update(Request $request, Employee $employee)
    {
        if (($validator = EmployeeRequest::validate())->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        $employee->update($data);

        return redirect()
            ->route('hr.employees.show', $employee)
            ->with('success', 'Employé mis à jour avec succès');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()
            ->route('hr.employees.index')
            ->with('success', 'Employé supprimé avec succès');
    }
}
