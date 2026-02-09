<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with(['head', 'teams'])->paginate(10);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $users = User::all();
        return view('departments.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id'
        ]);

        $department = Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Département créé avec succès.');
    }

    public function show(Department $department)
    {
        $department->load(['head', 'teams', 'users']);
        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $users = User::all();
        return view('departments.edit', compact('department', 'users'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id'
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Département mis à jour avec succès.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Département supprimé avec succès.');
    }
}