<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeEvaluationController extends Controller
{
    /**
     * Show the form for creating a new evaluation.
     */
    public function create(Employee $employee)
    {
        return view('employees.evaluations.create', compact('employee'));
    }

    /**
     * Store a newly created evaluation in storage.
     */
    public function store(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'period' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'objectives' => 'nullable|string',
            'achievements' => 'nullable|string',
            'areas_of_improvement' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        // Logic to store the evaluation would go here
        // For now, just redirect back with success message
        
        return redirect()->route('hr.employees.show', $employee)
            ->with('success', 'Évaluation créée avec succès.');
    }
}