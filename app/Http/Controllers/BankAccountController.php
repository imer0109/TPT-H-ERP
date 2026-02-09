<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Agency;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = BankAccount::with(['company', 'agency']);
        
        // Apply filters
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }
        
        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->get('agency_id'));
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }
        
        $bankAccounts = $query->paginate(10)->appends($request->except('page'));
        
        // Get filter options
        $companies = Company::all();
        $agencies = Agency::all();
        
        return view('bank-accounts.index', compact('bankAccounts', 'companies', 'agencies'));
    }

    public function create(Request $request)
    {
        $companies = Company::all();
        $agencies = Agency::all();
        
        // If a company_id or agency_id is provided in the request, pre-select it
        $companyId = $request->get('company_id');
        $agencyId = $request->get('agency_id');
        
        return view('bank-accounts.create', compact('companies', 'agencies', 'companyId', 'agencyId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'agency_id' => 'nullable|exists:agencies,id',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'iban' => 'nullable|string|max:255',
            'bic_swift' => 'nullable|string|max:255',
            'currency' => 'required|string|max:10',
            'account_type' => 'required|string|max:255',
            'balance' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        // Ensure either company_id or agency_id is provided
        if (!$validated['company_id'] && !$validated['agency_id']) {
            return redirect()->back()->withErrors(['entity' => 'Veuillez sélectionner une société ou une agence.']);
        }

        $bankAccount = BankAccount::create($validated);

        // Redirect based on which entity the bank account belongs to
        if ($bankAccount->company_id) {
            return redirect()->route('companies.dashboard.company', $bankAccount->company_id)
                ->with('success', 'Compte bancaire créé avec succès.');
        } else {
            return redirect()->route('companies.dashboard.agency', $bankAccount->agency_id)
                ->with('success', 'Compte bancaire créé avec succès.');
        }
    }

    public function edit(BankAccount $bankAccount)
    {
        $companies = Company::all();
        $agencies = Agency::all();
        
        return view('bank-accounts.edit', compact('bankAccount', 'companies', 'agencies'));
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'agency_id' => 'nullable|exists:agencies,id',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'iban' => 'nullable|string|max:255',
            'bic_swift' => 'nullable|string|max:255',
            'currency' => 'required|string|max:10',
            'account_type' => 'required|string|max:255',
            'balance' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        // Ensure either company_id or agency_id is provided
        if (!$validated['company_id'] && !$validated['agency_id']) {
            return redirect()->back()->withErrors(['entity' => 'Veuillez sélectionner une société ou une agence.']);
        }

        $bankAccount->update($validated);

        // Redirect based on which entity the bank account belongs to
        if ($bankAccount->company_id) {
            return redirect()->route('companies.dashboard.company', $bankAccount->company_id)
                ->with('success', 'Compte bancaire mis à jour avec succès.');
        } else {
            return redirect()->route('companies.dashboard.agency', $bankAccount->agency_id)
                ->with('success', 'Compte bancaire mis à jour avec succès.');
        }
    }

    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();
        
        // Redirect based on which entity the bank account belonged to
        if ($bankAccount->company_id) {
            return redirect()->route('companies.dashboard.company', $bankAccount->company_id)
                ->with('success', 'Compte bancaire supprimé avec succès.');
        } else {
            return redirect()->route('companies.dashboard.agency', $bankAccount->agency_id)
                ->with('success', 'Compte bancaire supprimé avec succès.');
        }
    }
}