<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Models\SupplierContract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupplierContractController extends Controller
{
    /**
     * Display a listing of the contracts.
     */
    public function index(Request $request)
    {
        $query = SupplierContract::with(['fournisseur', 'responsible']);

        // Filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('fournisseur', function($subQuery) use ($search) {
                      $subQuery->where('raison_sociale', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('fournisseur_id')) {
            $query->where('fournisseur_id', $request->input('fournisseur_id'));
        }

        if ($request->filled('expiring_soon')) {
            $query->where('end_date', '<=', now()->addDays(30))
                  ->where('end_date', '>=', now())
                  ->where('status', 'active');
        }

        $contracts = $query->orderBy('end_date', 'asc')->paginate(15);
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->get();
        $statuses = ['active', 'pending', 'expired', 'terminated'];

        return view('fournisseurs.contracts.index', compact('contracts', 'fournisseurs', 'statuses'));
    }

    /**
     * Show the form for creating a new contract.
     */
    public function create(Request $request)
    {
        $fournisseur = null;
        if ($request->filled('fournisseur_id')) {
            $fournisseur = Fournisseur::findOrFail($request->input('fournisseur_id'));
        }
        
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->get();
        $responsibles = User::orderBy('name')->get();

        return view('fournisseurs.contracts.create', compact('fournisseur', 'fournisseurs', 'responsibles'));
    }

    /**
     * Store a newly created contract in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'contract_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'renewal_date' => 'nullable|date|after:end_date',
            'auto_renewal' => 'nullable|boolean',
            'value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'terms' => 'nullable|string',
            'special_conditions' => 'nullable|string',
            'responsible_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $validated['contract_number'] = 'CTR-' . now()->format('Y') . '-' . strtoupper(uniqid());
        
        if (!isset($validated['auto_renewal'])) {
            $validated['auto_renewal'] = false;
        }

        $contract = SupplierContract::create($validated);

        return redirect()->route('fournisseurs.contracts.show', $contract)
            ->with('success', 'Contrat créé avec succès.');
    }

    /**
     * Display the specified contract.
     */
    public function show(SupplierContract $contract)
    {
        $contract->load(['fournisseur', 'responsible', 'documents']);

        return view('fournisseurs.contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified contract.
     */
    public function edit(SupplierContract $contract)
    {
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->get();
        $responsibles = User::orderBy('name')->get();

        return view('fournisseurs.contracts.edit', compact('contract', 'fournisseurs', 'responsibles'));
    }

    /**
     * Update the specified contract in storage.
     */
    public function update(Request $request, SupplierContract $contract)
    {
        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'contract_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'renewal_date' => 'nullable|date|after:end_date',
            'auto_renewal' => 'nullable|boolean',
            'value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'terms' => 'nullable|string',
            'special_conditions' => 'nullable|string',
            'responsible_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        if (!isset($validated['auto_renewal'])) {
            $validated['auto_renewal'] = false;
        }

        $contract->update($validated);

        return redirect()->route('fournisseurs.contracts.show', $contract)
            ->with('success', 'Contrat mis à jour avec succès.');
    }

    /**
     * Remove the specified contract from storage.
     */
    public function destroy(SupplierContract $contract)
    {
        $contract->delete();

        return redirect()->route('fournisseurs.contracts.index')
            ->with('success', 'Contrat supprimé avec succès.');
    }

    /**
     * Terminate the specified contract.
     */
    public function terminate(SupplierContract $contract)
    {
        $contract->update([
            'status' => 'terminated',
            'end_date' => now()
        ]);

        return redirect()->route('fournisseurs.contracts.show', $contract)
            ->with('success', 'Contrat résilié avec succès.');
    }

    /**
     * Renew the specified contract.
     */
    public function renew(SupplierContract $contract, Request $request)
    {
        $validated = $request->validate([
            'new_end_date' => 'required|date|after:' . $contract->end_date->format('Y-m-d'),
            'new_value' => 'nullable|numeric|min:0',
        ]);

        // Create a new contract based on the old one
        $newContract = $contract->replicate();
        $newContract->end_date = $validated['new_end_date'];
        $newContract->value = $validated['new_value'] ?? $contract->value;
        $newContract->start_date = $contract->end_date->addDay();
        $newContract->contract_number = 'CTR-' . now()->format('Y') . '-' . strtoupper(uniqid());
        $newContract->status = 'active';
        $newContract->save();

        return redirect()->route('fournisseurs.contracts.show', $newContract)
            ->with('success', 'Contrat renouvelé avec succès.');
    }

    /**
     * Get contracts expiring soon (for notifications).
     */
    public function expiringSoon()
    {
        $contracts = SupplierContract::with('fournisseur')
            ->where('status', 'active')
            ->where('end_date', '<=', now()->addDays(30))
            ->where('end_date', '>=', now())
            ->orderBy('end_date', 'asc')
            ->get();

        return response()->json([
            'count' => $contracts->count(),
            'contracts' => $contracts
        ]);
    }
}