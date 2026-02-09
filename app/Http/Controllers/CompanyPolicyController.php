<?php

namespace App\Http\Controllers;

use App\Models\CompanyPolicy;
use App\Models\Company;
use App\Models\Agency;
use Illuminate\Http\Request;

class CompanyPolicyController extends Controller
{
    public function index(Request $request)
    {
        $query = CompanyPolicy::with(['company', 'agency']);
        
        // Apply filters
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }
        
        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->get('agency_id'));
        }
        
        if ($request->filled('policy_type')) {
            $query->where('policy_type', $request->get('policy_type'));
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }
        
        $policies = $query->paginate(10)->appends($request->except('page'));
        
        // Get filter options
        $companies = Company::all();
        $agencies = Agency::all();
        $policyTypes = CompanyPolicy::select('policy_type')->distinct()->pluck('policy_type');
        
        return view('policies.index', compact('policies', 'companies', 'agencies', 'policyTypes'));
    }

    public function create(Request $request)
    {
        $companies = Company::all();
        $agencies = Agency::all();
        
        // If a company_id or agency_id is provided in the request, pre-select it
        $companyId = $request->get('company_id');
        $agencyId = $request->get('agency_id');
        
        return view('policies.create', compact('companies', 'agencies', 'companyId', 'agencyId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'agency_id' => 'nullable|exists:agencies,id',
            'policy_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:effective_date',
        ]);

        // Ensure either company_id or agency_id is provided
        if (!$validated['company_id'] && !$validated['agency_id']) {
            return redirect()->back()->withErrors(['entity' => 'Veuillez sélectionner une société ou une agence.']);
        }

        $policy = CompanyPolicy::create($validated);

        // Redirect based on which entity the policy belongs to
        if ($policy->company_id) {
            return redirect()->route('companies.dashboard.company', $policy->company_id)
                ->with('success', 'Politique créée avec succès.');
        } else {
            return redirect()->route('companies.dashboard.agency', $policy->agency_id)
                ->with('success', 'Politique créée avec succès.');
        }
    }

    public function edit(CompanyPolicy $policy)
    {
        $companies = Company::all();
        $agencies = Agency::all();
        
        return view('policies.edit', compact('policy', 'companies', 'agencies'));
    }

    public function update(Request $request, CompanyPolicy $policy)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'agency_id' => 'nullable|exists:agencies,id',
            'policy_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:effective_date',
        ]);

        // Ensure either company_id or agency_id is provided
        if (!$validated['company_id'] && !$validated['agency_id']) {
            return redirect()->back()->withErrors(['entity' => 'Veuillez sélectionner une société ou une agence.']);
        }

        $policy->update($validated);

        // Redirect based on which entity the policy belongs to
        if ($policy->company_id) {
            return redirect()->route('companies.dashboard.company', $policy->company_id)
                ->with('success', 'Politique mise à jour avec succès.');
        } else {
            return redirect()->route('companies.dashboard.agency', $policy->agency_id)
                ->with('success', 'Politique mise à jour avec succès.');
        }
    }

    public function destroy(CompanyPolicy $policy)
    {
        $policy->delete();
        
        // Redirect based on which entity the policy belonged to
        if ($policy->company_id) {
            return redirect()->route('companies.dashboard.company', $policy->company_id)
                ->with('success', 'Politique supprimée avec succès.');
        } else {
            return redirect()->route('companies.dashboard.agency', $policy->agency_id)
                ->with('success', 'Politique supprimée avec succès.');
        }
    }
}