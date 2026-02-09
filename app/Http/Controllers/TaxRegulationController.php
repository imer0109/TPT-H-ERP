<?php

namespace App\Http\Controllers;

use App\Models\TaxRegulation;
use App\Models\Company;
use App\Models\Agency;
use Illuminate\Http\Request;

class TaxRegulationController extends Controller
{
    public function index(Request $request)
    {
        $query = TaxRegulation::with(['company', 'agency']);
        
        // Apply filters
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }
        
        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->get('agency_id'));
        }
        
        if ($request->filled('tax_type')) {
            $query->where('tax_type', $request->get('tax_type'));
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }
        
        $taxRegulations = $query->paginate(10)->appends($request->except('page'));
        
        // Get filter options
        $companies = Company::all();
        $agencies = Agency::all();
        $taxTypes = TaxRegulation::select('tax_type')->distinct()->pluck('tax_type');
        
        return view('tax-regulations.index', compact('taxRegulations', 'companies', 'agencies', 'taxTypes'));
    }

    public function create(Request $request)
    {
        $companies = Company::all();
        $agencies = Agency::all();
        
        // If a company_id or agency_id is provided in the request, pre-select it
        $companyId = $request->get('company_id');
        $agencyId = $request->get('agency_id');
        
        return view('tax-regulations.create', compact('companies', 'agencies', 'companyId', 'agencyId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'agency_id' => 'nullable|exists:agencies,id',
            'tax_type' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:effective_date',
        ]);

        // Ensure either company_id or agency_id is provided
        if (!$validated['company_id'] && !$validated['agency_id']) {
            return redirect()->back()->withErrors(['entity' => 'Veuillez sélectionner une société ou une agence.']);
        }

        $taxRegulation = TaxRegulation::create($validated);

        // Redirect based on which entity the tax regulation belongs to
        if ($taxRegulation->company_id) {
            return redirect()->route('companies.dashboard.company', $taxRegulation->company_id)
                ->with('success', 'Réglementation fiscale créée avec succès.');
        } else {
            return redirect()->route('companies.dashboard.agency', $taxRegulation->agency_id)
                ->with('success', 'Réglementation fiscale créée avec succès.');
        }
    }

    public function edit(TaxRegulation $taxRegulation)
    {
        $companies = Company::all();
        $agencies = Agency::all();
        
        return view('tax-regulations.edit', compact('taxRegulation', 'companies', 'agencies'));
    }

    public function update(Request $request, TaxRegulation $taxRegulation)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'agency_id' => 'nullable|exists:agencies,id',
            'tax_type' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:effective_date',
        ]);

        // Ensure either company_id or agency_id is provided
        if (!$validated['company_id'] && !$validated['agency_id']) {
            return redirect()->back()->withErrors(['entity' => 'Veuillez sélectionner une société ou une agence.']);
        }

        $taxRegulation->update($validated);

        // Redirect based on which entity the tax regulation belongs to
        if ($taxRegulation->company_id) {
            return redirect()->route('companies.dashboard.company', $taxRegulation->company_id)
                ->with('success', 'Réglementation fiscale mise à jour avec succès.');
        } else {
            return redirect()->route('companies.dashboard.agency', $taxRegulation->agency_id)
                ->with('success', 'Réglementation fiscale mise à jour avec succès.');
        }
    }

    public function destroy(TaxRegulation $taxRegulation)
    {
        $taxRegulation->delete();
        
        // Redirect based on which entity the tax regulation belonged to
        if ($taxRegulation->company_id) {
            return redirect()->route('companies.dashboard.company', $taxRegulation->company_id)
                ->with('success', 'Réglementation fiscale supprimée avec succès.');
        } else {
            return redirect()->route('companies.dashboard.agency', $taxRegulation->agency_id)
                ->with('success', 'Réglementation fiscale supprimée avec succès.');
        }
    }
}