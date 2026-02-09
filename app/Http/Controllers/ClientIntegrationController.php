<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientIntegrationController extends Controller
{
    /**
     * Display a listing of client integrations
     */
    public function index(Request $request)
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('clients.integrations') && !Auth::user()->hasRole('commercial')) {
            abort(403, 'Accès non autorisé');
        }

        $query = ClientIntegration::with('client')->orderBy('created_at', 'desc');

        // Filtres
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('external_system', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($clientQuery) use ($search) {
                      $clientQuery->where('nom_raison_sociale', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('integration_type') && $request->input('integration_type') != '') {
            $query->where('integration_type', $request->input('integration_type'));
        }

        if ($request->has('sync_status') && $request->input('sync_status') != '') {
            $query->where('sync_status', $request->input('sync_status'));
        }

        $integrations = $query->paginate(15);

        return view('clients.integrations.index', compact('integrations'));
    }

    /**
     * Show the form for creating a new integration
     */
    public function create(Client $client)
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('clients.integrations') && !Auth::user()->hasRole('commercial')) {
            abort(403, 'Accès non autorisé');
        }

        return view('clients.integrations.create', compact('client'));
    }

    /**
     * Store a newly created integration
     */
    public function store(Request $request, Client $client)
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('clients.integrations') && !Auth::user()->hasRole('commercial')) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'integration_type' => 'required|string|max:50',
            'external_system' => 'required|string|max:100',
            'external_id' => 'nullable|string|max:100',
            'is_active' => 'boolean'
        ]);

        $validated['client_id'] = $client->id;
        $validated['sync_status'] = 'pending';

        $integration = ClientIntegration::create($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Intégration créée avec succès.');
    }

    /**
     * Show the form for editing the integration
     */
    public function edit(ClientIntegration $integration)
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('clients.integrations') && 
            !Auth::user()->hasRole('commercial') && 
            $integration->client->referent_commercial_id != Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('clients.integrations.edit', compact('integration'));
    }

    /**
     * Update the integration
     */
    public function update(Request $request, ClientIntegration $integration)
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('clients.integrations') && 
            !Auth::user()->hasRole('commercial') && 
            $integration->client->referent_commercial_id != Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'integration_type' => 'required|string|max:50',
            'external_system' => 'required|string|max:100',
            'external_id' => 'nullable|string|max:100',
            'is_active' => 'boolean'
        ]);

        $integration->update($validated);

        return redirect()->route('clients.show', $integration->client)
            ->with('success', 'Intégration mise à jour avec succès.');
    }

    /**
     * Remove the integration
     */
    public function destroy(ClientIntegration $integration)
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('clients.integrations') && !Auth::user()->hasRole('commercial')) {
            abort(403, 'Accès non autorisé');
        }

        $client = $integration->client;
        $integration->delete();

        return redirect()->route('clients.show', $client)
            ->with('success', 'Intégration supprimée avec succès.');
    }

    /**
     * Sync a client with external system
     */
    public function sync(ClientIntegration $integration)
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('clients.integrations') && 
            !Auth::user()->hasRole('commercial') && 
            $integration->client->referent_commercial_id != Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        // Simuler la synchronisation avec un système externe
        try {
            // Ici, on mettrait en place la logique réelle de synchronisation
            // avec des APIs externes comme Mailchimp, WhatsApp Business, etc.
            
            $integration->update([
                'sync_status' => 'synced',
                'last_sync_at' => now(),
                'sync_error_message' => null
            ]);

            return back()->with('success', 'Client synchronisé avec succès.');
        } catch (\Exception $e) {
            $integration->update([
                'sync_status' => 'failed',
                'sync_error_message' => $e->getMessage()
            ]);

            return back()->with('error', 'Erreur lors de la synchronisation: ' . $e->getMessage());
        }
    }

    /**
     * Export client segment to external marketing tool
     */
    public function exportSegment(Request $request)
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('clients.integrations.export') && !Auth::user()->hasRole('commercial')) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'segment' => 'required|string',
            'external_system' => 'required|string'
        ]);

        // Logique d'export vers un outil marketing externe
        // Cette méthode serait étendue pour intégrer des APIs spécifiques
        
        return back()->with('success', 'Segment exporté vers ' . $validated['external_system'] . ' avec succès.');
    }
}