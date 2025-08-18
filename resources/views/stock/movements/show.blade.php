@extends('layouts.app')

@section('title', 'Détails du Mouvement de Stock')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails du Mouvement de Stock</h3>
                    <div class="card-tools">
                        <a href="{{ route('stock.movements.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                        @if(!$movement->validated_by && auth()->user()->can('validate', $movement))
                            <form action="{{ route('stock.movements.validate', $movement) }}" method="POST" class="d-inline">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir valider ce mouvement?')">
                                    <i class="fas fa-check"></i> Valider
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h5 class="card-title">Informations Générales</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">Référence</th>
                                            <td>{{ $movement->reference ?: 'Non spécifiée' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td>
                                                @if($movement->type == 'entree')
                                                    <span class="badge badge-success">Entrée</span>
                                                @else
                                                    <span class="badge badge-danger">Sortie</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Date</th>
                                            <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Statut</th>
                                            <td>
                                                @if($movement->validated_by)
                                                    <span class="badge badge-success">Validé</span>
                                                @else
                                                    <span class="badge badge-warning">En attente</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Créé par</th>
                                            <td>{{ $movement->createdBy->name ?? 'Utilisateur inconnu' }}</td>
                                        </tr>
                                        @if($movement->validated_by)
                                        <tr>
                                            <th>Validé par</th>
                                            <td>{{ $movement->validatedBy->name ?? 'Utilisateur inconnu' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date de validation</th>
                                            <td>{{ $movement->validated_at ? $movement->validated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h5 class="card-title">Détails du Produit et Dépôt</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">Produit</th>
                                            <td>{{ $movement->product->nom ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Dépôt</th>
                                            <td>{{ $movement->warehouse->nom ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Quantité</th>
                                            <td>{{ $movement->quantite }}</td>
                                        </tr>
                                        <tr>
                                            <th>Prix Unitaire</th>
                                            <td>{{ number_format($movement->prix_unitaire, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Montant Total</th>
                                            <td>{{ number_format($movement->montant_total, 2) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-success">
                                    <h5 class="card-title">Informations Complémentaires</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="motif">Motif</label>
                                        <textarea class="form-control" id="motif" rows="3" readonly>{{ $movement->motif ?: 'Aucun motif spécifié' }}</textarea>
                                    </div>
                                    
                                    @if($movement->sourceEntity || $movement->destinationEntity)
                                    <div class="row">
                                        @if($movement->sourceEntity)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Source</label>
                                                <input type="text" class="form-control" value="{{ class_basename($movement->sourceEntity) }}: {{ $movement->sourceEntity->nom ?? $movement->sourceEntity->reference ?? 'N/A' }}" readonly>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($movement->destinationEntity)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Destination</label>
                                                <input type="text" class="form-control" value="{{ class_basename($movement->destinationEntity) }}: {{ $movement->destinationEntity->nom ?? $movement->destinationEntity->reference ?? 'N/A' }}" readonly>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection