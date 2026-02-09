<?php

namespace App\Http\Controllers;
use App\Models\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
     public function index()
    {
        $permissions = Permission::orderBy('module')->paginate(15);
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:permissions,nom',
            'module' => 'required|string|max:255',
            'resource' => 'nullable|string|max:255',
            'action' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Permission::create($validated);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission créée avec succès.');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:permissions,nom,' . $permission->id,
            'module' => 'required|string|max:255',
            'resource' => 'nullable|string|max:255',
            'action' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $permission->update($validated);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission mise à jour avec succès.');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->exists()) {
            return redirect()->route('permissions.index')
                ->with('error', 'Cette permission ne peut pas être supprimée car elle est utilisée par des rôles.');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission supprimée avec succès.');
    }
}