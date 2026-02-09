<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PositionController extends Controller
{
    /**
     * Display a listing of the positions.
     */
    public function index()
    {
        $positions = Position::with(['department', 'parentPosition'])->get();
        return view('positions.index', compact('positions'));
    }

    /**
     * Show the form for creating a new position.
     */
    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        return view('positions.create', compact('departments', 'positions'));
    }

    /**
     * Store a newly created position in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'parent_position_id' => 'nullable|exists:positions,id',
            'is_management' => 'boolean',
        ]);

        $position = Position::create($validatedData);

        return redirect()->route('positions.index')
            ->with('success', 'Poste créé avec succès.');
    }

    /**
     * Display the specified position.
     */
    public function show(Position $position)
    {
        $position->load(['department', 'parentPosition', 'childPositions', 'employees']);
        return view('positions.show', compact('position'));
    }

    /**
     * Show the form for editing the specified position.
     */
    public function edit(Position $position)
    {
        $departments = Department::all();
        $positions = Position::where('id', '!=', $position->id)->get();
        return view('positions.edit', compact('position', 'departments', 'positions'));
    }

    /**
     * Update the specified position in storage.
     */
    public function update(Request $request, Position $position)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'parent_position_id' => 'nullable|exists:positions,id',
            'is_management' => 'boolean',
        ]);

        $position->update($validatedData);

        return redirect()->route('positions.index')
            ->with('success', 'Poste mis à jour avec succès.');
    }

    /**
     * Remove the specified position from storage.
     */
    public function destroy(Position $position)
    {
        // Check if position has employees or child positions
        if ($position->employees()->exists() || $position->childPositions()->exists()) {
            return redirect()->route('positions.index')
                ->with('error', 'Impossible de supprimer ce poste car il est utilisé.');
        }

        $position->delete();

        return redirect()->route('positions.index')
            ->with('success', 'Poste supprimé avec succès.');
    }

    /**
     * Generate organizational chart
     */
    public function organizationalChart()
    {
        // Get all positions with their relationships
        $positions = Position::with(['department', 'parentPosition', 'employees'])->get();
        
        // Build hierarchical structure
        $rootPositions = $positions->filter(function ($position) {
            return is_null($position->parent_position_id);
        });

        return view('positions.organizational-chart', compact('rootPositions', 'positions'));
    }
}
