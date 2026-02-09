@extends('layouts.app')

@section('title', 'Détails de l\'Interaction')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Détails de l'Interaction #{{ $interaction->id }}</h4>
                    <div class="card-tools">
                        <a href="{{ route('clients.interactions.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Client:</label>
                                <p><a href="{{ route('clients.show', $interaction->client) }}">{{ $interaction->client->nom_raison_sociale }}</a></p>
                            </div>
                            
                            <div class="form-group">
                                <label>Type d'Interaction:</label>
                                <p>
                                    <span class="badge badge-{{ $interaction->type_interaction == 'appel_telephonique' ? 'primary' : 
                                            ($interaction->type_interaction == 'visite_commerciale' ? 'info' : 
                                            ($interaction->type_interaction == 'email' ? 'success' : 'warning')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $interaction->type_interaction)) }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="form-group">
                                <label>Utilisateur:</label>
                                <p>{{ $interaction->user ? $interaction->user->nom . ' ' . $interaction->user->prenom : 'N/A' }}</p>
                            </div>
                            
                            <div class="form-group">
                                <label>Date de l'Interaction:</label>
                                <p>{{ $interaction->date_interaction->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Suivi Nécessaire:</label>
                                <p>
                                    @if($interaction->suivi_necessaire)
                                        <span class="badge badge-danger">Oui</span>
                                    @else
                                        <span class="badge badge-success">Non</span>
                                    @endif
                                </p>
                            </div>
                            
                            @if($interaction->suivi_necessaire && $interaction->date_suivi)
                            <div class="form-group">
                                <label>Date de Suivi:</label>
                                <p>{{ $interaction->date_suivi->format('d/m/Y') }}</p>
                            </div>
                            @endif
                            
                            @if($interaction->campagne_id)
                            <div class="form-group">
                                <label>ID de Campagne:</label>
                                <p>{{ $interaction->campagne_id }}</p>
                            </div>
                            @endif
                            
                            <div class="form-group">
                                <label>Date de Création:</label>
                                <p>{{ $interaction->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            <div class="form-group">
                                <label>Dernière Mise à Jour:</label>
                                <p>{{ $interaction->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Description:</label>
                        <p class="form-control-static">{{ $interaction->description }}</p>
                    </div>
                    
                    @if($interaction->resultat)
                    <div class="form-group">
                        <label>Résultat:</label>
                        <p class="form-control-static">{{ $interaction->resultat }}</p>
                    </div>
                    @endif
                    
                    <!-- Actions -->
                    <div class="form-group">
                        <a href="{{ route('clients.interactions.edit', $interaction) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        
                        <form action="{{ route('clients.interactions.destroy', $interaction) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette interaction ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                        
                        @if($interaction->suivi_necessaire)
                        <form action="{{ route('clients.interactions.mark-as-followed-up', $interaction) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning" title="Marquer comme suivi">
                                <i class="fas fa-check"></i> Marquer comme suivi
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection