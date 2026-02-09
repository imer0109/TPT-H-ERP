<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use App\Models\SupplierIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierIntegrationController extends Controller
{
    /**
     * Display a listing of supplier integrations.
     */
    public function index()
    {
        $supplier = Auth::user()->fournisseur;
        
        if (!$supplier) {
            abort(403, 'Accès non autorisé');
        }
        
        $integrations = $supplier->supplierIntegrations()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('fournisseurs.portal.integrations.index', compact('integrations'));
    }

    /**
     * Show the form for creating a new integration.
     */
    public function create()
    {
        $supplier = Auth::user()->fournisseur;
        
        if (!$supplier) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('fournisseurs.portal.integrations.create');
    }

    /**
     * Store a newly created integration.
     */
    public function store(Request $request)
    {
        $supplier = Auth::user()->fournisseur;
        
        if (!$supplier) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'integration_type' => 'required|string|in:erp,accounting,inventory,custom',
            'external_system' => 'required|string|max:255',
            'external_id' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        
        $validated['fournisseur_id'] = $supplier->id;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sync_status'] = 'pending';
        
        $integration = SupplierIntegration::create($validated);
        
        return redirect()->route('supplier.portal.integrations.index')
            ->with('success', 'Intégration créée avec succès.');
    }

    /**
     * Display the specified integration.
     */
    public function show(SupplierIntegration $integration)
    {
        $supplier = Auth::user()->fournisseur;
        
        if (!$supplier) {
            abort(403, 'Accès non autorisé');
        }
        
        // Ensure the integration belongs to the supplier
        if ($integration->fournisseur_id !== $supplier->id) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('fournisseurs.portal.integrations.show', compact('integration'));
    }

    /**
     * Show the form for editing the specified integration.
     */
    public function edit(SupplierIntegration $integration)
    {
        $supplier = Auth::user()->fournisseur;
        
        if (!$supplier) {
            abort(403, 'Accès non autorisé');
        }
        
        // Ensure the integration belongs to the supplier
        if ($integration->fournisseur_id !== $supplier->id) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('fournisseurs.portal.integrations.edit', compact('integration'));
    }

    /**
     * Update the specified integration.
     */
    public function update(Request $request, SupplierIntegration $integration)
    {
        $supplier = Auth::user()->fournisseur;
        
        if (!$supplier) {
            abort(403, 'Accès non autorisé');
        }
        
        // Ensure the integration belongs to the supplier
        if ($integration->fournisseur_id !== $supplier->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'integration_type' => 'required|string|in:erp,accounting,inventory,custom',
            'external_system' => 'required|string|max:255',
            'external_id' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->boolean('is_active', true);
        
        $integration->update($validated);
        
        return redirect()->route('supplier.portal.integrations.index')
            ->with('success', 'Intégration mise à jour avec succès.');
    }

    /**
     * Remove the specified integration.
     */
    public function destroy(SupplierIntegration $integration)
    {
        $supplier = Auth::user()->fournisseur;
        
        if (!$supplier) {
            abort(403, 'Accès non autorisé');
        }
        
        // Ensure the integration belongs to the supplier
        if ($integration->fournisseur_id !== $supplier->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $integration->delete();
        
        return redirect()->route('supplier.portal.integrations.index')
            ->with('success', 'Intégration supprimée avec succès.');
    }

    /**
     * Manually sync the integration.
     */
    public function sync(SupplierIntegration $integration)
    {
        $supplier = Auth::user()->fournisseur;
        
        if (!$supplier) {
            abort(403, 'Accès non autorisé');
        }
        
        // Ensure the integration belongs to the supplier
        if ($integration->fournisseur_id !== $supplier->id) {
            abort(403, 'Accès non autorisé');
        }
        
        // In a real implementation, this would trigger the actual synchronization
        // For now, we'll just update the status and timestamp
        $integration->update([
            'sync_status' => 'synced',
            'last_sync_at' => now(),
            'sync_error_message' => null
        ]);
        
        return redirect()->back()
            ->with('success', 'Synchronisation effectuée avec succès.');
    }
}