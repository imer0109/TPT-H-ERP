<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientInteraction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClientInteractionController extends Controller
{
    /**
     * Afficher la liste des interactions
     */
    public function index(Request $request)
    {
        // Vérifier les permissions
        // Temporairement désactivé pour permettre l'accès
        /*if (!Auth::user()->hasRole('administrateur') && !Auth::user()->hasRole('admin') && (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.interactions.view') && !Auth::user()->hasRole('manager') && !Auth::user()->hasRole('commercial'))) {
            abort(403, 'Accès non autorisé');
        }*/
        
        $query = ClientInteraction::with(['client', 'user'])
            ->orderBy('date_interaction', 'desc');

        // Filtres
        if ($request->has('client_id') && $request->input('client_id') != '') {
            $query->where('client_id', $request->input('client_id'));
        }

        if ($request->has('user_id') && $request->input('user_id') != '') {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->has('type_interaction') && $request->input('type_interaction') != '') {
            $query->where('type_interaction', $request->input('type_interaction'));
        }

        if ($request->has('date_debut') && $request->input('date_debut') != '') {
            $query->whereDate('date_interaction', '>=', $request->input('date_debut'));
        }

        if ($request->has('date_fin') && $request->input('date_fin') != '') {
            $query->whereDate('date_interaction', '<=', $request->input('date_fin'));
        }

        if ($request->has('suivi_necessaire') && $request->input('suivi_necessaire') != '') {
            $query->where('suivi_necessaire', $request->input('suivi_necessaire') === '1');
        }

        $interactions = $query->paginate(15);
        $clients = Client::all();
        $users = User::all();

        return view('clients.interactions.index', compact('interactions', 'clients', 'users'));
    }

    /**
     * Afficher le formulaire de création d'une interaction
     */
    public function create(Request $request)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.interactions.create')) {
            abort(403, 'Accès non autorisé');
        }
        
        $clients = Client::all();
        $users = User::all();
        $client_id = $request->input('client_id');
        $client = $client_id ? Client::find($client_id) : null;

        return view('clients.interactions.create', compact('clients', 'users', 'client'));
    }

    /**
     * Enregistrer une nouvelle interaction
     */
    public function store(Request $request)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.interactions.create')) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'user_id' => 'required|exists:users,id',
            'type_interaction' => ['required', Rule::in([
                'appel_telephonique',
                'visite_commerciale',
                'email',
                'message_whatsapp',
                'reunion',
                'autre'
            ])],
            'description' => 'required|string',
            'date_interaction' => 'required|date',
            'resultat' => 'nullable|string',
            'suivi_necessaire' => 'boolean',
            'date_suivi' => 'nullable|date|required_if:suivi_necessaire,1',
            'campagne_id' => 'nullable|integer',
        ]);

        // Si l'utilisateur n'est pas spécifié, utiliser l'utilisateur connecté
        if (!isset($validated['user_id'])) {
            $validated['user_id'] = Auth::id();
        }

        try {
            $interaction = ClientInteraction::create($validated);

            return redirect()->route('clients.interactions.show', $interaction)
                ->with('success', 'Interaction créée avec succès.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la création de l\'interaction: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les détails d'une interaction
     */
    public function show(ClientInteraction $interaction)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.interactions.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $interaction->load(['client', 'user']);
        return view('clients.interactions.show', compact('interaction'));
    }

    /**
     * Afficher le formulaire de modification d'une interaction
     */
    public function edit(ClientInteraction $interaction)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.interactions.edit')) {
            abort(403, 'Accès non autorisé');
        }
        
        $clients = Client::all();
        $users = User::all();

        return view('clients.interactions.edit', compact('interaction', 'clients', 'users'));
    }

    /**
     * Mettre à jour une interaction
     */
    public function update(Request $request, ClientInteraction $interaction)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.interactions.edit')) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'user_id' => 'required|exists:users,id',
            'type_interaction' => ['required', Rule::in([
                'appel_telephonique',
                'visite_commerciale',
                'email',
                'message_whatsapp',
                'reunion',
                'autre'
            ])],
            'description' => 'required|string',
            'date_interaction' => 'required|date',
            'resultat' => 'nullable|string',
            'suivi_necessaire' => 'boolean',
            'date_suivi' => 'nullable|date|required_if:suivi_necessaire,1',
            'campagne_id' => 'nullable|integer',
        ]);

        try {
            $interaction->update($validated);

            return redirect()->route('clients.interactions.show', $interaction)
                ->with('success', 'Interaction mise à jour avec succès.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la mise à jour de l\'interaction: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une interaction
     */
    public function destroy(ClientInteraction $interaction)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.interactions.delete')) {
            abort(403, 'Accès non autorisé');
        }
        
        try {
            $interaction->delete();

            return redirect()->route('clients.interactions.index')
                ->with('success', 'Interaction supprimée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression de l\'interaction: ' . $e->getMessage());
        }
    }

    /**
     * Marquer une interaction comme suivie
     */
    public function markAsFollowedUp(ClientInteraction $interaction)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.interactions.edit')) {
            abort(403, 'Accès non autorisé');
        }
        
        try {
            $interaction->suivi_necessaire = false;
            $interaction->save();

            return back()->with('success', 'Interaction marquée comme suivie.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les interactions d'un client spécifique
     */
    public function clientInteractions(Client $client)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.interactions.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $interactions = ClientInteraction::where('client_id', $client->id)
            ->with(['user'])
            ->orderBy('date_interaction', 'desc')
            ->paginate(15);

        return view('clients.interactions.client', compact('client', 'interactions'));
    }

    /**
     * Afficher les interactions nécessitant un suivi
     */
    public function followUps()
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.interactions.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $interactions = ClientInteraction::with(['client', 'user'])
            ->where('suivi_necessaire', true)
            ->whereDate('date_suivi', '<=', now()->addDays(7))
            ->orderBy('date_suivi')
            ->paginate(15);

        return view('clients.interactions.follow-ups', compact('interactions'));
    }
}