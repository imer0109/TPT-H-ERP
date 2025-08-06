<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function index()
    {
        $agencies = Agency::with(['company', 'responsable'])->paginate(10);
        return view('agencies.index', compact('agencies'));
    }

    public function create()
    {
        $companies = Company::all();
        $users = User::all();
        return view('agencies.create', compact('companies', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code_unique' => 'required|string|max:255|unique:agencies',
            'adresse' => 'required|string',
            'responsable_id' => 'required|exists:users,id',
            'zone_geographique' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'company_id' => 'required|exists:companies,id'
        ]);

        Agency::create($validated);

        return redirect()->route('agencies.index')
            ->with('success', 'Agence créée avec succès.');
    }

    public function edit(Agency $agency)
    {
        $companies = Company::all();
        $users = User::all();
        return view('agencies.edit', compact('agency', 'companies', 'users'));
    }

    public function update(Request $request, Agency $agency)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code_unique' => 'required|string|max:255|unique:agencies,code_unique,' . $agency->id,
            'adresse' => 'required|string',
            'responsable_id' => 'required|exists:users,id',
            'zone_geographique' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'company_id' => 'required|exists:companies,id'
        ]);

        $agency->update($validated);

        return redirect()->route('agencies.index')
            ->with('success', 'Agence mise à jour avec succès.');
    }

    public function destroy(Agency $agency)
    {
        $agency->delete();
        return redirect()->route('agencies.index')
            ->with('success', 'Agence supprimée avec succès.');
    }
}