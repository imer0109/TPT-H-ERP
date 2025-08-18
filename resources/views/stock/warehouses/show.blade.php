@extends('layouts.app')

@section('title', 'Détails du Dépôt')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails du Dépôt: {{ $warehouse->nom }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('stock.warehouses.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                        <a href="{{ route('stock.warehouses.edit', $warehouse) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
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
                                            <th style="width: 30%">Code</th>
                                            <td>{{ $warehouse->code }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nom</th>
                                            <td>{{ $warehouse->nom }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $warehouse->description ?: 'Non spécifiée' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td>{{ $warehouse->type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Adresse</th>
                                            <td>{{ $warehouse->adresse ?: 'Non spécifiée' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Statut</th>
                                            <td>
                                                @if($warehouse->actif)
                                                    <span class="badge badge-success">Actif</span>
                                                @else
                                                    <span class="badge badge-danger">Inactif</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Créé par</th>
                                            <td>{{ $warehouse->createdBy->name ?? 'Utilisateur inconnu' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date de création</th>
                                            <td>{{ $warehouse->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Dernière mise à jour</th>
                                            <td>{{ $warehouse->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h5 class="card-title">Statistiques</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-info"><i class="fas fa-boxes"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Mouvements de Stock</span>
                                                    <span class="info-box-number">{{ $warehouse->stockMovements->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Alertes</span>
                                                    <span class="info-box-number">{{ $warehouse->alerts->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header bg-success">
                                    <h5 class="card-title">Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="{{ route('stock.movements.create', ['warehouse_id' => $warehouse->id]) }}" class="btn btn-block btn-primary">
                                                <i class="fas fa-plus"></i> Nouveau Mouvement
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ route('stock.inventories.create', ['warehouse_id' => $warehouse->id]) }}" class="btn btn-block btn-info">
                                                <i class="fas fa-clipboard-list"></i> Nouvel Inventaire
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <a href="{{ route('stock.transfers.create', ['source_id' => $warehouse->id]) }}" class="btn btn-block btn-warning">
                                                <i class="fas fa-exchange-alt"></i> Nouveau Transfert
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ route('stock.reports.current-stock', ['warehouse_id' => $warehouse->id]) }}" class="btn btn-block btn-success">
                                                <i class="fas fa-chart-bar"></i> Rapport de Stock
                                            </a>
                                        </div>
                                    </div>
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