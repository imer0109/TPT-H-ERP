<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAssignment;
use App\Models\Company;
use App\Models\Agency;
use App\Models\Warehouse;
use App\Models\Position;
use Illuminate\Http\Request;

class EmployeeAssignmentController extends Controller
{
    /**
     * Show the form for creating a new assignment.
     */
    public function create(Employee $employee)
    {
        $companies = Company::all();
        $agencies = Agency::all();
        $warehouses = Warehouse::all();
        $positions = Position::all();
        
        return view('employees.assignments.create', compact(
            'employee', 
            'companies', 
            'agencies', 
            'warehouses', 
            'positions'
        ));
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'agency_id' => 'nullable|exists:agencies,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'position_id' => 'nullable|exists:positions,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_primary' => 'boolean'
        ]);

        // If this is set as primary, unset other primary assignments for this employee
        if (isset($validated['is_primary']) && $validated['is_primary']) {
            EmployeeAssignment::where('employee_id', $employee->id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        $employee->assignments()->create($validated);

        return redirect()->route('hr.employees.show', $employee)
            ->with('success', 'Affectation créée avec succès.');
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(Employee $employee, EmployeeAssignment $assignment)
    {
        $companies = Company::all();
        $agencies = Agency::all();
        $warehouses = Warehouse::all();
        $positions = Position::all();
        
        return view('employees.assignments.edit', compact(
            'employee', 
            'assignment',
            'companies', 
            'agencies', 
            'warehouses', 
            'positions'
        ));
    }

    /**
     * Update the specified assignment in storage.
     */
    public function update(Request $request, Employee $employee, EmployeeAssignment $assignment)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'agency_id' => 'nullable|exists:agencies,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'position_id' => 'nullable|exists:positions,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_primary' => 'boolean',
            'status' => 'in:active,inactive,suspended'
        ]);

        // If this is set as primary, unset other primary assignments for this employee
        if (isset($validated['is_primary']) && $validated['is_primary']) {
            EmployeeAssignment::where('employee_id', $employee->id)
                ->where('id', '!=', $assignment->id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        $assignment->update($validated);

        return redirect()->route('hr.employees.show', $employee)
            ->with('success', 'Affectation mise à jour avec succès.');
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy(Employee $employee, EmployeeAssignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('hr.employees.show', $employee)
            ->with('success', 'Affectation supprimée avec succès.');
    }
}