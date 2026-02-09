<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Models\Company;
use App\Models\FournisseurDocument;
use App\Traits\ChecksSupplierPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FournisseurController extends Controller
{
    use ChecksSupplierPermissions;

    /**
     * Affiche la liste des fournisseurs
     */
    public function index(Request $request)
    {
        if (!$this->checkSupplierPermission('suppliers.fournisseurs.view')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour voir les fournisseurs.');
        }
        
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
        $societes = Company::orderBy('raison_sociale')->get();

        return view('fournisseurs.index', compact('fournisseurs', 'societes'));
    }

    /**
     * Affiche le formulaire de création d'un fournisseur
     */
    public function create()
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.fournisseurs.create')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour créer un fournisseur.');
        }
        
        $societes = Company::orderBy('raison_sociale')->get();
        return view('fournisseurs.create', compact('societes'));
    }

    /**
     * Enregistre un nouveau fournisseur
     */
    public function store(Request $request)
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.fournisseurs.create')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour créer un fournisseur.');
        }
        
        // Validation des données
        $validated = $request->validate([
            'societe_id' => 'required|exists:companies,id',
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
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.fournisseurs.view')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour voir les fournisseurs.');
        }
        
        $fournisseur->load(['societe', 'documents']);
        
        // Récupérer les commandes, livraisons, paiements et réclamations associés
        // Note: Ces relations doivent être définies dans le modèle Fournisseur
        $commandes = $fournisseur->supplierOrders()->latest()->take(5)->get();
        $livraisons = $fournisseur->supplierDeliveries()->latest()->take(5)->get();
        $paiements = $fournisseur->supplierPayments()->latest()->take(5)->get();
        $reclamations = $fournisseur->supplierIssues()->latest()->take(5)->get();
        
        return view('fournisseurs.show', compact(
            'fournisseur', 'commandes', 'livraisons', 'paiements', 'reclamations'
        ));
    }

    /**
     * Affiche le formulaire d'édition d'un fournisseur
     */
    public function edit(Fournisseur $fournisseur)
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.fournisseurs.edit')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour modifier un fournisseur.');
        }
        
        $societes = Company::orderBy('raison_sociale')->get();
        $fournisseur->load('documents');
        
        return view('fournisseurs.edit', compact('fournisseur', 'societes'));
    }

    /**
     * Met à jour un fournisseur
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.fournisseurs.edit')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour modifier un fournisseur.');
        }
        
        // Validation des données
        $validated = $request->validate([
            'societe_id' => 'required|exists:companies,id',
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

    /**
     * Supprime un fournisseur
     */
    public function destroy(Fournisseur $fournisseur)
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.fournisseurs.delete')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour supprimer un fournisseur.');
        }
        
        // Supprimer les documents associés
        foreach ($fournisseur->documents as $document) {
            Storage::disk('public')->delete($document->chemin_fichier);
            $document->delete();
        }

        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur supprimé avec succès.');
    }

    /**
     * Exporte les fournisseurs en CSV
     */
    public function export()
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.fournisseurs.export')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour exporter les fournisseurs.');
        }
        
        $fournisseurs = Fournisseur::with('societe')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="fournisseurs.csv"',
        ];

        $callback = function() use ($fournisseurs) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'Code Fournisseur',
                'Raison Sociale',
                'Type',
                'Activité',
                'Statut',
                'Téléphone',
                'Email',
                'Ville',
                'Pays'
            ]);
            
            // Données
            foreach ($fournisseurs as $fournisseur) {
                fputcsv($file, [
                    $fournisseur->code_fournisseur,
                    $fournisseur->raison_sociale,
                    $fournisseur->type,
                    $fournisseur->activite,
                    $fournisseur->statut,
                    $fournisseur->telephone,
                    $fournisseur->email,
                    $fournisseur->ville,
                    $fournisseur->pays
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Traite le téléchargement des documents
     */
    private function handleDocumentUploads(Request $request, Fournisseur $fournisseur)
    {
        $documentTypes = [
            'contrat_cadre' => 'Contrat Cadre',
            'rccm_document' => 'RCCM',
            'attestation_fiscale' => 'Attestation Fiscale',
            'autre_document' => 'Autre Document'
        ];

        foreach ($documentTypes as $inputName => $documentType) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $filename = Str::slug($fournisseur->raison_sociale) . '_' . $documentType . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('fournisseurs/documents', $filename, 'public');

                FournisseurDocument::create([
                    'fournisseur_id' => $fournisseur->id,
                    'type_document' => $documentType,
                    'nom_fichier' => $file->getClientOriginalName(),
                    'chemin_fichier' => $path,
                    'taille_fichier' => $file->getSize(),
                ]);
            }
        }
    }

    /**
     * Affiche les fournisseurs par activité
     */
    public function parActivite($activite)
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.fournisseurs.view')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour voir les fournisseurs.');
        }
        
        $fournisseurs = Fournisseur::with('societe')
            ->where('activite', $activite)
            ->orderBy('raison_sociale')
            ->paginate(15);

        $societes = Company::orderBy('raison_sociale')->get();

        return view('fournisseurs.index', compact('fournisseurs', 'societes'))
            ->with('activite_filter', $activite);
    }

    /**
     * Affiche les fournisseurs actifs
     */
    public function actifs()
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.fournisseurs.view')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour voir les fournisseurs.');
        }
        
        $fournisseurs = Fournisseur::with('societe')
            ->where('statut', 'actif')
            ->orderBy('raison_sociale')
            ->paginate(15);

        $societes = Company::orderBy('raison_sociale')->get();

        return view('fournisseurs.index', compact('fournisseurs', 'societes'))
            ->with('statut_filter', 'actif');
    }

    /**
     * Affiche les fournisseurs inactifs
     */
    public function inactifs()
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.fournisseurs.view')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour voir les fournisseurs.');
        }
        
        $fournisseurs = Fournisseur::with('societe')
            ->where('statut', 'inactif')
            ->orderBy('raison_sociale')
            ->paginate(15);

        $societes = Company::orderBy('raison_sociale')->get();

        return view('fournisseurs.index', compact('fournisseurs', 'societes'))
            ->with('statut_filter', 'inactif');
    }
}