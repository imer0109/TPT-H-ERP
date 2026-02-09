<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\LoyaltyCard;
use App\Models\LoyaltyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoyaltyCardController extends Controller
{
    public function dashboard()
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.loyalty.view') && !Auth::user()->hasRole('commercial')) {
            abort(403, 'Accès non autorisé');
        }

        // Statistiques globales
        $totalCards = LoyaltyCard::count();
        $totalPoints = LoyaltyCard::sum('points');
        $activeCards = LoyaltyCard::where('status', 'active')->count();
        $platinumMembers = LoyaltyCard::where('tier', 'platinum')->count();

        // Répartition par niveau
        $tierDistribution = LoyaltyCard::select('tier', DB::raw('count(*) as card_count'), DB::raw('sum(points) as total_points'))
            ->groupBy('tier')
            ->get();

        // Transactions récentes
        $recentTransactions = LoyaltyTransaction::with('loyaltyCard.client')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Top clients par points
        $topClients = LoyaltyCard::with('client')
            ->orderBy('points', 'desc')
            ->take(10)
            ->get();

        return view('clients.loyalty.dashboard', compact(
            'totalCards',
            'totalPoints',
            'activeCards',
            'platinumMembers',
            'tierDistribution',
            'recentTransactions',
            'topClients'
        ));
    }

    /**
     * Display a listing of loyalty cards
     */
    public function index(Request $request)
    {
        // Vérifier les permissions
        // Temporairement désactivé pour permettre l'accès
        /*if (!Auth::user()->hasRole('administrateur') && !Auth::user()->hasRole('admin') && (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.loyalty.index') && !Auth::user()->hasRole('manager') && !Auth::user()->hasRole('commercial'))) {
            abort(403, 'Accès non autorisé');
        }*/
        
        $query = LoyaltyCard::with('client')->orderBy('created_at', 'desc');

        // Filtres
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('card_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($clientQuery) use ($search) {
                      $clientQuery->where('nom_raison_sociale', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('tier') && $request->input('tier') != '') {
            $query->where('tier', $request->input('tier'));
        }

        if ($request->has('status') && $request->input('status') != '') {
            $query->where('status', $request->input('status'));
        }

        $loyaltyCards = $query->paginate(15);

        return view('clients.loyalty.index', compact('loyaltyCards'));
    }

    /**
     * Show the form for creating a new loyalty card
     */
    public function create(Client $client)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.loyalty.create') && !Auth::user()->hasRole('commercial')) {
            abort(403, 'Accès non autorisé');
        }

        return view('clients.loyalty.create', compact('client'));
    }

    /**
     * Store a newly created loyalty card
     */
    public function store(Request $request, Client $client)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.loyalty.create') && !Auth::user()->hasRole('commercial')) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'points' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,suspended',
            'expires_at' => 'nullable|date|after:today'
        ]);

        // Vérifier si le client a déjà une carte de fidélité
        if ($client->loyaltyCard) {
            return back()->with('error', 'Ce client possède déjà une carte de fidélité.');
        }

        $validated['client_id'] = $client->id;
        $validated['card_number'] = LoyaltyCard::generateCardNumber();
        $validated['issued_at'] = now();
        $validated['points'] = $validated['points'] ?? 0;

        $loyaltyCard = LoyaltyCard::create($validated);

        // Mettre à jour le niveau de la carte
        $loyaltyCard->updateTier();
        $loyaltyCard->save();

        return redirect()->route('clients.show', $client)
            ->with('success', 'Carte de fidélité créée avec succès.');
    }

    /**
     * Show the form for editing the loyalty card
     */
    public function edit(LoyaltyCard $loyaltyCard)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.loyalty.edit') && 
            !Auth::user()->hasRole('commercial') && 
            $loyaltyCard->client->referent_commercial_id != Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('clients.loyalty.edit', compact('loyaltyCard'));
    }

    /**
     * Update the loyalty card
     */
    public function update(Request $request, LoyaltyCard $loyaltyCard)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.loyalty.edit') && 
            !Auth::user()->hasRole('commercial') && 
            $loyaltyCard->client->referent_commercial_id != Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'points' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,suspended',
            'expires_at' => 'nullable|date'
        ]);

        $loyaltyCard->update($validated);

        // Mettre à jour le niveau de la carte
        $loyaltyCard->updateTier();
        $loyaltyCard->save();

        return redirect()->route('clients.show', $loyaltyCard->client)
            ->with('success', 'Carte de fidélité mise à jour avec succès.');
    }

    /**
     * Remove the loyalty card
     */
    public function destroy(LoyaltyCard $loyaltyCard)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.loyalty.delete') && !Auth::user()->hasRole('commercial')) {
            abort(403, 'Accès non autorisé');
        }

        $client = $loyaltyCard->client;
        $loyaltyCard->delete();

        return redirect()->route('clients.show', $client)
            ->with('success', 'Carte de fidélité supprimée avec succès.');
    }

    /**
     * Add points to loyalty card
     */
    public function addPoints(Request $request, LoyaltyCard $loyaltyCard)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.loyalty.edit') && 
            !Auth::user()->hasRole('commercial') && 
            $loyaltyCard->client->referent_commercial_id != Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'points' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255'
        ]);

        $loyaltyCard->addPoints($validated['points']);

        return back()->with('success', $validated['points'] . ' points ajoutés avec succès.');
    }

    /**
     * Redeem points from loyalty card
     */
    public function redeemPoints(Request $request, LoyaltyCard $loyaltyCard)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAccessModule('clients') && !Auth::user()->hasPermission('clients.loyalty.edit') && 
            !Auth::user()->hasRole('commercial') && 
            $loyaltyCard->client->referent_commercial_id != Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'points' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255'
        ]);

        if ($loyaltyCard->usePoints($validated['points'])) {
            return back()->with('success', $validated['points'] . ' points utilisés avec succès.');
        }

        return back()->with('error', 'Points insuffisants pour effectuer cette opération.');
    }
}