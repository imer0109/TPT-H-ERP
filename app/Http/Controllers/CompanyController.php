<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with('parent')->paginate(10);
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        $companies = Company::where('type', 'holding')->get();
        return view('companies.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raison_sociale' => 'required|string|max:255',
            'type' => 'required|in:holding,filiale',
            'niu' => 'nullable|string|max:255',
            'rccm' => 'nullable|string|max:255',
            'regime_fiscal' => 'nullable|string|max:255',
            'secteur_activite' => 'required|string|max:255',
            'devise' => 'required|string|max:10',
            'pays' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'siege_social' => 'required|string',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'site_web' => 'nullable|url|max:255',
            'parent_id' => 'nullable|exists:companies,id',
            'logo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        Company::create($validated);

        return redirect()->route('companies.index')
            ->with('success', 'Société créée avec succès.');
    }

    public function edit(Company $company)
    {
        $companies = Company::where('type', 'holding')
            ->where('id', '!=', $company->id)
            ->get();
        return view('companies.edit', compact('company', 'companies'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'raison_sociale' => 'required|string|max:255',
            'type' => 'required|in:holding,filiale',
            'niu' => 'nullable|string|max:255',
            'rccm' => 'nullable|string|max:255',
            'regime_fiscal' => 'nullable|string|max:255',
            'secteur_activite' => 'required|string|max:255',
            'devise' => 'required|string|max:10',
            'pays' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'siege_social' => 'required|string',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'site_web' => 'nullable|url|max:255',
            'parent_id' => 'nullable|exists:companies,id',
            'logo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $company->update($validated);

        return redirect()->route('companies.index')
            ->with('success', 'Société mise à jour avec succès.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')
            ->with('success', 'Société supprimée avec succès.');
    }
}