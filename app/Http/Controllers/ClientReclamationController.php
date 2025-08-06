<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientReclamation;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClientReclamationController extends Controller
{
    /**
     * Afficher la liste des réclamations
     */
    public function index(Request $request)
    {
        $query = ClientReclamation::with(['client', 'agent'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->has('client_id') && $request->input('client_id') != '') {
            $query->where('client_id', $request->input('client_id'));
        }

        if ($request->has('statut') && $request->input('statut') != '') {
            $query->where('statut', $request->input('statut'));
        }

        if ($request->has('type_reclamation') && $request->input('type_reclamation') != '') {
            $query->where('type_reclamation', $request->input('type_reclamation'));
        }

        if ($request->has('agent_id') && $request->input('agent_id') != '') {
            $query->where('agent_id', $request->input('agent_id'));
        }

        if ($request->has('date_debut') && $request->input('date_debut') != '') {
            $query->whereDate('created_at', '>=', $request->input('date_debut'));
        }

        if ($request->has('date_fin') && $request->input('date_fin') != '') {
            $query->whereDate('created_at', '<=', $request->input('date_fin'));
        }

        $reclamations = $query->paginate(15);
        $clients = Client::all();
        $agents = User::all();

        return view('clients.reclamations.index', compact('reclamations', 'clients', 'agents'));
    }

    /**
     * Afficher le formulaire de création d'une réclamation
     */
    public function create(Request $request)
    {
        $clients = Client::all();
        $agents = User::all();
        $client_id = $request->input('client_id');
        $client = $client_id ? Client::find($client_id) : null;

        return view('clients.reclamations.create', compact('clients', 'agents', 'client'));
    }

    /**
     * Enregistrer une nouvelle réclamation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type_reclamation' => ['required', Rule::in([
                'produit_defectueux',
                'retard_livraison',
                'erreur_facturation',
                'service_client',
                'qualite_produit',
                'autre'
            ])],
            'description' => 'required|string',
            'statut' => ['required', Rule::in(['ouverte', 'en_cours', 'resolue'])],
            'agent_id' => 'nullable|exists:users,id',
            'date_resolution' => 'nullable|date',
            'solution' => 'nullable|string',
            'commentaires' => 'nullable|string',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();

        try {
            $reclamation = ClientReclamation::create($validated);

            // Traitement des documents
            if ($request->hasFile('documents')) {
                $documents = $request->file('documents');

                foreach ($documents as $file) {
                    $path = $file->store('documents/reclamations/' . $reclamation->id, 'public');

                    Document::create([
                        'nom' => $file->getClientOriginalName(),
                        'type_document' => 'autre',
                        'chemin_fichier' => $path,
                        'taille' => $file->getSize(),
                        'format' => $file->getClientOriginalExtension(),
                        'user_id' => Auth::id(),
                        'documentable_id' => $reclamation->id,
                        'documentable_type' => ClientReclamation::class,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('clients.reclamations.show', $reclamation)
                ->with('success', 'Réclamation créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la création de la réclamation: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les détails d'une réclamation
     */
    public function show(ClientReclamation $reclamation)
    {
        $reclamation->load(['client', 'agent', 'documents']);
        return view('clients.reclamations.show', compact('reclamation'));
    }

    /**
     * Afficher le formulaire de modification d'une réclamation
     */
    public function edit(ClientReclamation $reclamation)
    {
        $clients = Client::all();
        $agents = User::all();
        $documents = $reclamation->documents;

        return view('clients.reclamations.edit', compact('reclamation', 'clients', 'agents', 'documents'));
    }

    /**
     * Mettre à jour une réclamation
     */
    public function update(Request $request, ClientReclamation $reclamation)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type_reclamation' => ['required', Rule::in([
                'produit_defectueux',
                'retard_livraison',
                'erreur_facturation',
                'service_client',
                'qualite_produit',
                'autre'
            ])],
            'description' => 'required|string',
            'statut' => ['required', Rule::in(['ouverte', 'en_cours', 'resolue'])],
            'agent_id' => 'nullable|exists:users,id',
            'date_resolution' => 'nullable|date',
            'solution' => 'nullable|string',
            'commentaires' => 'nullable|string',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();

        try {
            // Si le statut passe à résolu, on enregistre la date de résolution
            if ($validated['statut'] === 'resolue' && $reclamation->statut !== 'resolue') {
                $validated['date_resolution'] = now();
            }

            $reclamation->update($validated);

            // Traitement des documents
            if ($request->hasFile('documents')) {
                $documents = $request->file('documents');

                foreach ($documents as $file) {
                    $path = $file->store('documents/reclamations/' . $reclamation->id, 'public');

                    Document::create([
                        'nom' => $file->getClientOriginalName(),
                        'type_document' => 'autre',
                        'chemin_fichier' => $path,
                        'taille' => $file->getSize(),
                        'format' => $file->getClientOriginalExtension(),
                        'user_id' => Auth::id(),
                        'documentable_id' => $reclamation->id,
                        'documentable_type' => ClientReclamation::class,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('clients.reclamations.show', $reclamation)
                ->with('success', 'Réclamation mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la mise à jour de la réclamation: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une réclamation
     */
    public function destroy(ClientReclamation $reclamation)
    {
        try {
            // Supprimer les documents associés à la réclamation
            foreach ($reclamation->documents as $document) {
                Storage::disk('public')->delete($document->chemin_fichier);
                $document->delete();
            }

            $reclamation->delete();

            return redirect()->route('clients.reclamations.index')
                ->with('success', 'Réclamation supprimée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression de la réclamation: ' . $e->getMessage());
        }
    }

    /**
     * Changer le statut d'une réclamation
     */
    public function changeStatus(Request $request, ClientReclamation $reclamation)
    {
        $validated = $request->validate([
            'statut' => ['required', Rule::in(['ouverte', 'en_cours', 'resolue'])],
            'commentaires' => 'nullable|string',
        ]);

        try {
            // Si le statut passe à résolu, on enregistre la date de résolution
            if ($validated['statut'] === 'resolue' && $reclamation->statut !== 'resolue') {
                $reclamation->date_resolution = now();
            }

            $reclamation->statut = $validated['statut'];
            
            if ($request->has('commentaires')) {
                $reclamation->commentaires = $validated['commentaires'];
            }
            
            $reclamation->save();

            return back()->with('success', 'Statut de la réclamation mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut: ' . $e->getMessage());
        }
    }

    /**
     * Assigner un agent à une réclamation
     */
    public function assignAgent(Request $request, ClientReclamation $reclamation)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:users,id',
        ]);

        try {
            $reclamation->agent_id = $validated['agent_id'];
            $reclamation->save();

            return back()->with('success', 'Agent assigné avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'assignation de l\'agent: ' . $e->getMessage());
        }
    }
}