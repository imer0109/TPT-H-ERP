@extends('layouts.app')

@section('title', 'Modifier la Réclamation')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Modifier la Réclamation #{{ $reclamation->id }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('clients.reclamations.update', $reclamation) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client_id">Client <span class="text-danger">*</span></label>
                                    <select name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror" required>
                                        <option value="">Sélectionnez un client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id', $reclamation->client_id) == $client->id ? 'selected' : '' }}>
                                                {{ $client->nom_raison_sociale }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_reclamation">Type de Réclamation <span class="text-danger">*</span></label>
                                    <select name="type_reclamation" id="type_reclamation" class="form-control @error('type_reclamation') is-invalid @enderror" required>
                                        <option value="">Sélectionnez un type</option>
                                        <option value="produit_defectueux" {{ old('type_reclamation', $reclamation->type_reclamation) == 'produit_defectueux' ? 'selected' : '' }}>
                                            Produit Défectueux
                                        </option>
                                        <option value="retard_livraison" {{ old('type_reclamation', $reclamation->type_reclamation) == 'retard_livraison' ? 'selected' : '' }}>
                                            Retard de Livraison
                                        </option>
                                        <option value="erreur_facturation" {{ old('type_reclamation', $reclamation->type_reclamation) == 'erreur_facturation' ? 'selected' : '' }}>
                                            Erreur de Facturation
                                        </option>
                                        <option value="service_client" {{ old('type_reclamation', $reclamation->type_reclamation) == 'service_client' ? 'selected' : '' }}>
                                            Service Client
                                        </option>
                                        <option value="qualite_produit" {{ old('type_reclamation', $reclamation->type_reclamation) == 'qualite_produit' ? 'selected' : '' }}>
                                            Qualité du Produit
                                        </option>
                                        <option value="autre" {{ old('type_reclamation', $reclamation->type_reclamation) == 'autre' ? 'selected' : '' }}>
                                            Autre
                                        </option>
                                    </select>
                                    @error('type_reclamation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="statut">Statut <span class="text-danger">*</span></label>
                                    <select name="statut" id="statut" class="form-control @error('statut') is-invalid @enderror" required>
                                        <option value="ouverte" {{ old('statut', $reclamation->statut) == 'ouverte' ? 'selected' : '' }}>Ouverte</option>
                                        <option value="en_cours" {{ old('statut', $reclamation->statut) == 'en_cours' ? 'selected' : '' }}>En Cours</option>
                                        <option value="resolue" {{ old('statut', $reclamation->statut) == 'resolue' ? 'selected' : '' }}>Résolue</option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="agent_id">Agent Assigné</label>
                                    <select name="agent_id" id="agent_id" class="form-control @error('agent_id') is-invalid @enderror">
                                        <option value="">Sélectionnez un agent</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}" {{ old('agent_id', $reclamation->agent_id) == $agent->id ? 'selected' : '' }}>
                                                {{ $agent->nom }} {{ $agent->prenom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('agent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Décrivez la réclamation..." required>{{ old('description', $reclamation->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="solution">Solution</label>
                            <textarea name="solution" id="solution" rows="3" class="form-control @error('solution') is-invalid @enderror" placeholder="Solution proposée...">{{ old('solution', $reclamation->solution) }}</textarea>
                            @error('solution')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="commentaires">Commentaires</label>
                            <textarea name="commentaires" id="commentaires" rows="2" class="form-control @error('commentaires') is-invalid @enderror" placeholder="Commentaires...">{{ old('commentaires', $reclamation->commentaires) }}</textarea>
                            @error('commentaires')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="documents">Documents (facultatif)</label>
                            <input type="file" name="documents[]" id="documents" class="form-control @error('documents') is-invalid @enderror" multiple>
                            <small class="form-text text-muted">Vous pouvez sélectionner plusieurs fichiers (PDF, JPG, PNG, max 10MB chacun)</small>
                            @error('documents')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour la Réclamation
                            </button>
                            <a href="{{ route('clients.reclamations.show', $reclamation) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
