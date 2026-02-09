<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with(['leader', 'department'])->paginate(10);
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        $users = User::all();
        $departments = Department::all();
        return view('teams.create', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'leader_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        $team = Team::create($validated);

        return redirect()->route('teams.index')
            ->with('success', 'Équipe créée avec succès.');
    }

    public function show(Team $team)
    {
        $team->load(['leader', 'department', 'users']);
        return view('teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $users = User::all();
        $departments = Department::all();
        return view('teams.edit', compact('team', 'users', 'departments'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'leader_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        $team->update($validated);

        return redirect()->route('teams.index')
            ->with('success', 'Équipe mise à jour avec succès.');
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Équipe supprimée avec succès.');
    }
}