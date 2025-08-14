<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use App\Models\Agency;
use App\Models\Warehouse;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Position;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::with(['company', 'agency', 'warehouse', 'position', 'supervisor'])
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
        $companies = Company::pluck('name', 'id');
        $agencies = Agency::pluck('name', 'id');
        $warehouses = Warehouse::pluck('name', 'id');
        $positions = Position::pluck('title', 'id');
        $supervisors = Employee::where('is_supervisor', true)
            ->get()
            ->map(function($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->nom . ' ' . $employee->prenom
                ];
            })
            ->pluck('name', 'id');

        return view('employees.create', compact(
            'companies',
            'agencies',
            'warehouses',
            'positions',
            'supervisors'
        ));
    }

    public function store(EmployeeRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        Employee::create($data);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employé créé avec succès');
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'company',
            'agency',
            'warehouse',
            'position',
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
        $companies = Company::pluck('name', 'id');
        $agencies = Agency::pluck('name', 'id');
        $warehouses = Warehouse::pluck('name', 'id');
        $positions = Position::pluck('title', 'id');
        $supervisors = Employee::where('is_supervisor', true)
            ->where('id', '!=', $employee->id)
            ->get()
            ->map(function($emp) {
                return [
                    'id' => $emp->id,
                    'name' => $emp->nom . ' ' . $emp->prenom
                ];
            })
            ->pluck('name', 'id');

        return view('employees.edit', compact(
            'employee',
            'companies',
            'agencies',
            'warehouses',
            'positions',
            'supervisors'
        ));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        $employee->update($data);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Employé mis à jour avec succès');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employé supprimé avec succès');
    }
}
