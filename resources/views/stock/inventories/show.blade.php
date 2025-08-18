@extends('layouts.app')

@section('title', 'Détails de l\'Inventaire')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails de l'Inventaire #{{ $inventory->reference }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('stock.inventories.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                        <a href="#" class="btn btn-success btn-sm" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimer
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Informations Générales</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">Référence</th>
                                            <td>{{ $inventory->reference }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date</th>
                                            <td>{{ $inventory->date->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Dépôt</th>
                                            <td>{{ $inventory->warehouse->nom }}</td>
                                        </tr>
                                        <tr>
                                            <th>Statut</th>
                                            <td>
                                                @if($inventory->status == 'en_cours')
                                                    <span class="badge badge-warning">En cours</span>
                                                @elseif($inventory->status == 'valide')
                                                    <span class="badge badge-success">Validé</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Notes</th>
                                            <td>{{ $inventory->notes ?? 'Aucune note' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Informations Complémentaires</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">Créé par</th>
                                            <td>{{ $inventory->createdBy->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date de création</th>
                                            <td>{{ $inventory->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Validé par</th>
                                            <td>{{ $inventory->validatedBy->name ?? 'Non validé' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date de validation</th>
                                            <td>{{ $inventory->validated_at ? $inventory->validated_at->format('d/m/Y H:i') : 'Non validé' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nombre de produits</th>
                                            <td>{{ $inventory->items->count() }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Détails de l'Inventaire</h3>
                                    <div class="card-tools">
                                        @if($inventory->status == 'en_cours')
                                            <a href="{{ route('stock.inventories.edit', $inventory) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Produit</th>
                                                    <th>Référence</th>
                                                    <th>Stock Théorique</th>
                                                    <th>Stock Réel</th>
                                                    <th>Différence</th>
                                                    <th>Valeur Différence</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalDifference = 0;
                                                    $totalDifferenceValue = 0;
                                                @endphp
                                                
                                                @foreach($inventory->items as $item)
                                                    @php
                                                        $differenceValue = $item->difference !== null ? $item->difference * $item->product->prix_achat : 0;
                                                        $totalDifference += $item->difference ?? 0;
                                                        $totalDifferenceValue += $differenceValue;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $item->product->nom }}</td>
                                                        <td>{{ $item->product->reference }}</td>
                                                        <td class="text-right">{{ number_format($item->theoretical_quantity, 2) }}</td>
                                                        <td class="text-right">
                                                            {{ $item->actual_quantity !== null ? number_format($item->actual_quantity, 2) : '-' }}
                                                        </td>
                                                        <td class="text-right">
                                                            @if($item->difference !== null)
                                                                <span class="{{ $item->difference < 0 ? 'text-danger' : ($item->difference > 0 ? 'text-success' : '') }}">
                                                                    {{ number_format($item->difference, 2) }}
                                                                </span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="text-right">
                                                            @if($item->difference !== null)
                                                                <span class="{{ $differenceValue < 0 ? 'text-danger' : ($differenceValue > 0 ? 'text-success' : '') }}">
                                                                    {{ number_format($differenceValue, 2) }}
                                                                </span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->notes ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" class="text-right">Total des Différences:</th>
                                                    <th class="text-right">
                                                        <span class="{{ $totalDifference < 0 ? 'text-danger' : ($totalDifference > 0 ? 'text-success' : '') }}">
                                                            {{ number_format($totalDifference, 2) }}
                                                        </span>
                                                    </th>
                                                    <th class="text-right">
                                                        <span class="{{ $totalDifferenceValue < 0 ? 'text-danger' : ($totalDifferenceValue > 0 ? 'text-success' : '') }}">
                                                            {{ number_format($totalDifferenceValue, 2) }}
                                                        </span>
                                                    </th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($inventory->status == 'en_cours')
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <form action="{{ route('stock.inventories.validate', $inventory) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir valider cet inventaire? Cette action est irréversible.')">
                                        <i class="fas fa-check"></i> Valider l'Inventaire
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection