<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use App\Models\Agency;
use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Afficher la liste des clients
     */
    public function index(Request $request)
    {
        $query = Client::with(['company', 'agency', 'referentCommercial'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nom_raison_sociale', 'like', "%{$search}%")
                  ->orWhere('code_client', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        if ($request->has('company_id') && $request->input('company_id') != '') {
            $query->where('company_id', $request->input('company_id'));
        }

        if ($request->has('agency_id') && $request->input('agency_id') != '') {
            $query->where('agency_id', $request->input('agency_id'));
        }

        if ($request->has('type_client') && $request->input('type_client') != '') {
            $query->where('type_client', $request->input('type_client'));
        }

        if ($request->has('statut') && $request->input('statut') != '') {
            $query->where('statut', $request->input('statut'));
        }

        if ($request->has('categorie') && $request->input('categorie') != '') {
            $query->where('categorie', $request->input('categorie'));
        }

        $clients = $query->paginate(15);
        $companies = Company::all();
        $agencies = Agency::all();

        return view('clients.index', compact('clients', 'companies', 'agencies'));
    }

    /**
     * Afficher le formulaire de création d'un client
     */
    public function create()
    {
        $companies = Company::all();
        $agencies = Agency::all();
        $commerciaux = User::whereHas('roles', function ($query) {
            $query->where('nom', 'commercial');
        })->get();

        // Générer un code client unique
        $codeClient = Client::generateUniqueCode();

        return view('clients.create', compact('companies', 'agencies', 'commerciaux', 'codeClient'));
    }

    /**
     * Enregistrer un nouveau client
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_client' => 'required|unique:clients,code_client',
            'company_id' => 'required|exists:companies,id',
            'agency_id' => 'nullable|exists:agencies,id',
            'nom_raison_sociale' => 'required|string|max:255',
            'type_client' => ['required', Rule::in(['particulier', 'entreprise', 'administration', 'distributeur'])],
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
            'contact_principal' => 'nullable|string|max:255',
            'canal_acquisition' => 'nullable|string|max:255',
            'referent_commercial_id' => 'nullable|exists:users,id',
            'type_relation' => ['required', Rule::in(['comptant', 'credit', 'vip'])],
            'delai_paiement' => 'nullable|integer|min:0',
            'plafond_credit' => 'nullable|numeric|min:0',
            'mode_paiement_prefere' => 'nullable|string|max:255',
            'statut' => ['required', Rule::in(['actif', 'inactif', 'suspendu'])],
            'categorie' => ['required', Rule::in(['or', 'argent', 'bronze'])],
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'types_documents' => 'nullable|array',
            'types_documents.*' => 'string',
        ]);

        DB::beginTransaction();

        try {
            $client = Client::create($validated);

            // Traitement des documents
            if ($request->hasFile('documents')) {
                $documents = $request->file('documents');
                $typesDocuments = $request->input('types_documents', []);

                foreach ($documents as $key => $file) {
                    $path = $file->store('documents/clients/' . $client->id, 'public');
                    $typeDocument = isset($typesDocuments[$key]) ? $typesDocuments[$key] : 'autre';

                    Document::create([
                        'nom' => $file->getClientOriginalName(),
                        'type_document' => $typeDocument,
                        'chemin_fichier' => $path,
                        'taille' => $file->getSize(),
                        'format' => $file->getClientOriginalExtension(),
                        'user_id' => Auth::id(),
                        'documentable_id' => $client->id,
                        'documentable_type' => Client::class,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('clients.show', $client)
                ->with('success', 'Client créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la création du client: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les détails d'un client
     */
    public function show(Client $client)
    {
        $client->load(['company', 'agency', 'referentCommercial', 'documents', 'reclamations', 'interactions']);
        
        // Calculer les statistiques financières
        $encours = $client->getEncours();
        $delaiMoyenReglement = $client->getDelaiMoyenReglement();
        $nombreFacturesImpayees = $client->getNombreFacturesImpayees();
        
        // Récupérer les transactions récentes
        $transactions = $client->transactions()->latest()->take(10)->get();
        
        return view('clients.show', compact(
            'client', 
            'encours', 
            'delaiMoyenReglement', 
            'nombreFacturesImpayees', 
            'transactions'
        ));
    }

    /**
     * Afficher le formulaire de modification d'un client
     */
    public function edit(Client $client)
    {
        $companies = Company::all();
        $agencies = Agency::all();
        $commerciaux = User::whereHas('roles', function ($query) {
            $query->where('nom', 'commercial');
        })->get();
        $documents = $client->documents;

        return view('clients.edit', compact('client', 'companies', 'agencies', 'commerciaux', 'documents'));
    }

    /**
     * Mettre à jour un client
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'code_client' => ['required', Rule::unique('clients')->ignore($client->id)],
            'company_id' => 'required|exists:companies,id',
            'agency_id' => 'nullable|exists:agencies,id',
            'nom_raison_sociale' => 'required|string|max:255',
            'type_client' => ['required', Rule::in(['particulier', 'entreprise', 'administration', 'distributeur'])],
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
            'contact_principal' => 'nullable|string|max:255',
            'canal_acquisition' => 'nullable|string|max:255',
            'referent_commercial_id' => 'nullable|exists:users,id',
            'type_relation' => ['required', Rule::in(['comptant', 'credit', 'vip'])],
            'delai_paiement' => 'nullable|integer|min:0',
            'plafond_credit' => 'nullable|numeric|min:0',
            'mode_paiement_prefere' => 'nullable|string|max:255',
            'statut' => ['required', Rule::in(['actif', 'inactif', 'suspendu'])],
            'categorie' => ['required', Rule::in(['or', 'argent', 'bronze'])],
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'types_documents' => 'nullable|array',
            'types_documents.*' => 'string',
        ]);

        DB::beginTransaction();

        try {
            $client->update($validated);

            // Traitement des documents
            if ($request->hasFile('documents')) {
                $documents = $request->file('documents');
                $typesDocuments = $request->input('types_documents', []);

                foreach ($documents as $key => $file) {
                    $path = $file->store('documents/clients/' . $client->id, 'public');
                    $typeDocument = isset($typesDocuments[$key]) ? $typesDocuments[$key] : 'autre';

                    Document::create([
                        'nom' => $file->getClientOriginalName(),
                        'type_document' => $typeDocument,
                        'chemin_fichier' => $path,
                        'taille' => $file->getSize(),
                        'format' => $file->getClientOriginalExtension(),
                        'user_id' => Auth::id(),
                        'documentable_id' => $client->id,
                        'documentable_type' => Client::class,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('clients.show', $client)
                ->with('success', 'Client mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la mise à jour du client: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un client
     */
    public function destroy(Client $client)
    {
        try {
            // Supprimer les documents associés au client
            foreach ($client->documents as $document) {
                Storage::disk('public')->delete($document->chemin_fichier);
                $document->delete();
            }

            $client->delete();

            return redirect()->route('clients.index')
                ->with('success', 'Client supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression du client: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le tableau de bord des clients
     */
    public function dashboard()
    {
        // Statistiques globales
        $totalClients = Client::count();
        $clientsActifs = Client::where('statut', 'actif')->count();
        $clientsInactifs = Client::where('statut', 'inactif')->count();
        $clientsSuspendus = Client::where('statut', 'suspendu')->count();

        // Top 10 clients par CA
        $topClients = Client::withCount(['transactions as ca' => function ($query) {
            $query->where('type', 'encaissement');
            $query->select(DB::raw('SUM(montant)'));
        }])->orderBy('ca', 'desc')->take(10)->get();

        // Répartition par type de client
        $repartitionTypes = Client::select('type_client', DB::raw('count(*) as total'))
            ->groupBy('type_client')
            ->get();

        // Répartition par catégorie
        $repartitionCategories = Client::select('categorie', DB::raw('count(*) as total'))
            ->groupBy('categorie')
            ->get();

        // Évolution du nombre de clients dans le temps (par mois)
        $evolutionClients = Client::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as total')
        )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('clients.dashboard', compact(
            'totalClients',
            'clientsActifs',
            'clientsInactifs',
            'clientsSuspendus',
            'topClients',
            'repartitionTypes',
            'repartitionCategories',
            'evolutionClients'
        ));
    }

    /**
     * Télécharger un document
     */
    public function downloadDocument(Document $document)
    {
        // Vérifier si l'utilisateur a le droit d'accéder à ce document
        if ($document->documentable_type === Client::class) {
            $client = Client::find($document->documentable_id);
            
            // Vérifier si l'utilisateur a accès à ce client (selon les règles d'accès)
            // Logique d'autorisation à implémenter selon les besoins
            
            return Storage::disk('public')->download($document->chemin_fichier, $document->nom);
        }
        
        abort(403, 'Accès non autorisé');
    }

    /**
     * Supprimer un document
     */
    public function deleteDocument(Document $document)
    {
        // Vérifier si l'utilisateur a le droit de supprimer ce document
        if ($document->documentable_type === Client::class) {
            $client = Client::find($document->documentable_id);
            
            // Vérifier si l'utilisateur a accès à ce client (selon les règles d'accès)
            // Logique d'autorisation à implémenter selon les besoins
            
            Storage::disk('public')->delete($document->chemin_fichier);
            $document->delete();
            
            return back()->with('success', 'Document supprimé avec succès.');
        }
        
        abort(403, 'Accès non autorisé');
    }

    /**
     * Exporter les clients au format Excel ou PDF
     */
    public function export(Request $request)
    {
        // Logique d'export à implémenter selon les besoins
        // Utiliser une bibliothèque comme Laravel Excel ou DomPDF
        
        return back()->with('info', 'Fonctionnalité d\'export en cours de développement.');
    }
}