<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeContractController extends Controller
{
    /**
     * Show the form for creating a new contract.
     */
    public function create(Employee $employee)
    {
        return view('employees.contracts.create', compact('employee'));
    }

    /**
     * Store a newly created contract in storage.
     */
    public function store(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'salary' => 'required|numeric|min:0',
            'terms' => 'nullable|string',
        ]);

        // Logic to store the contract would go here
        // For now, just redirect back with success message
        
        return redirect()->route('hr.employees.show', $employee)
            ->with('success', 'Contrat créé avec succès.');
    }
}