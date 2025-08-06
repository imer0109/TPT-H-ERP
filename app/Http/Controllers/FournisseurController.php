<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Models\FournisseurDocument;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FournisseurController extends Controller
{
    /**
     * Affiche la liste des fournisseurs
     */
    public function index(Request $request)
    {
        $query = Fournisseur::with('societe');

        // Filtres
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('code_fournisseur', 'like', "%{$search}%")
                  ->orWhere('raison_sociale', 'like', "%{$search}%")
                  ->orWhere('contact_principal', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('societe_id')) {
            $query->where('societe_id', $request->input('societe_id'));
        }

        if ($request->filled('activite')) {
            $query->where('activite', $request->input('activite'));
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        $fournisseurs = $query->orderBy('raison_sociale')->paginate(15);
        $societes = Societe::orderBy('nom')->get();

        return view('fournisseurs.index', compact('fournisseurs', 'societes'));
    }

    /**
     * Affiche le formulaire de création d'un fournisseur
     */
    public function create()
    {
        $societes = Societe::orderBy('nom')->get();
        return view('fournisseurs.create', compact('societes'));
    }

    /**
     * Enregistre un nouveau fournisseur
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'societe_id' => 'required|exists:societes,id',
            'raison_sociale' => 'required|string|max:255',
            'type' => 'required|string|in:personne_physique,entreprise,institution',
            'activite' => 'required|string|in:transport,logistique,matieres_premieres,services,autre',
            'statut' => 'nullable|string|in:actif,inactif',
            'niu' => 'nullable|string|max:50',
            'rccm' => 'nullable|string|max:50',
            'cnss' => 'nullable|string|max:50',
            'adresse' => 'required|string',
            'pays' => 'required|string|max:100',
            'ville' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'site_web' => 'nullable|url|max:255',
            'contact_principal' => 'required|string|max:255',
            'banque' => 'nullable|string|max:255',
            'numero_compte' => 'nullable|string|max:255',
            'devise' => 'nullable|string|max:10',
            'condition_reglement' => 'nullable|string|in:comptant,credit',
            'delai_paiement' => 'nullable|integer|min:0',
            'plafond_credit' => 'nullable|numeric|min:0',
            'date_debut_relation' => 'nullable|date',
            'contrat_cadre' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'rccm_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'attestation_fiscale' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'autre_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Valeur par défaut pour le statut
        if (!isset($validated['statut'])) {
            $validated['statut'] = 'actif';
        }

        // Création du fournisseur
        $fournisseur = Fournisseur::create($validated);

        // Traitement des documents
        $this->handleDocumentUploads($request, $fournisseur);

        return redirect()->route('fournisseurs.show', $fournisseur)
            ->with('success', 'Fournisseur créé avec succès.');
    }

    /**
     * Affiche les détails d'un fournisseur
     */
    public function show(Fournisseur $fournisseur)
    {
        $fournisseur->load(['societe', 'documents']);
        
        // Récupérer les commandes, livraisons, paiements et réclamations associés
        // Note: Ces relations doivent être définies dans le modèle Fournisseur
        $commandes = $fournisseur->commandes()->latest()->take(5)->get();
        $livraisons = $fournisseur->livraisons()->latest()->take(5)->get();
        $paiements = $fournisseur->paiements()->latest()->take(5)->get();
        $reclamations = $fournisseur->reclamations()->latest()->take(5)->get();
        
        return view('fournisseurs.show', compact(
            'fournisseur', 'commandes', 'livraisons', 'paiements', 'reclamations'
        ));
    }

    /**
     * Affiche le formulaire d'édition d'un fournisseur
     */
    public function edit(Fournisseur $fournisseur)
    {
        $societes = Societe::orderBy('nom')->get();
        $fournisseur->load('documents');
        
        return view('fournisseurs.edit', compact('fournisseur', 'societes'));
    }

    /**
     * Met à jour un fournisseur
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        // Validation des données
        $validated = $request->validate([
            'societe_id' => 'required|exists:societes,id',
            'raison_sociale' => 'required|string|max:255',
            'type' => 'required|string|in:personne_physique,entreprise,institution',
            'activite' => 'required|string|in:transport,logistique,matieres_premieres,services,autre',
            'statut' => 'required|string|in:actif,inactif',
            'niu' => 'nullable|string|max:50',
            'rccm' => 'nullable|string|max:50',
            'cnss' => 'nullable|string|max:50',
            'adresse' => 'required|string',
            'pays' => 'required|string|max:100',
            'ville' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'site_web' => 'nullable|url|max:255',
            'contact_principal' => 'required|string|max:255',
            'banque' => 'nullable|string|max:255',
            'numero_compte' => 'nullable|string|max:255',
            'devise' => 'nullable|string|max:10',
            'condition_reglement' => 'nullable|string|in:comptant,credit',
            'delai_paiement' => 'nullable|integer|min:0',
            'plafond_credit' => 'nullable|numeric|min:0',
            'date_debut_relation' => 'nullable|date',
            'contrat_cadre' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'rccm_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'attestation_fiscale' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'autre_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Mise à jour du fournisseur
        $fournisseur->update($validated);

        // Traitement des documents
        $this->handleDocumentUploads($request, $fournisseur);

        return redirect()->route('fournisseurs.show', $fournisseur)
            ->with('success', 'Fournisseur mis à jour avec succès.');
    }