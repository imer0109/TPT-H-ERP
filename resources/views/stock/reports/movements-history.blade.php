@extends('layouts.app')

@section('title', 'Historique des Mouvements de Stock')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Historique des Mouvements de Stock</h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-success btn-sm" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimer
                        </a>
                        <a href="#" class="btn btn-primary btn-sm" id="export-excel">
                            <i class="fas fa-file-excel"></i> Exporter Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock.reports.movements-history') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Dépôt</label>
                                    <select name="warehouse_id" class="form-control">
                                        <option value="">Tous les dépôts</option>
                                        @foreach($warehouses as $id => $name)
                                            <option value="{{ $id }}" {{ request('warehouse_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Produit</label>
                                    <select name="product_id" class="form-control">
                                        <option value="">Tous les produits</option>
                                        @foreach($products as $id => $name)
                                            <option value="{{ $id }}" {{ request('product_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select name="type" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrée</option>
                                        <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Date début</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Date fin</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filtrer
                                </button>
                                <a href="{{ route('stock.reports.movements-history') }}" class="btn btn-secondary">
                                    <i class="fas fa-sync"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Dépôt</th>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Montant Total</th>
                                    <th>Motif</th>
                                    <th>Créé par</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movements as $movement)
                                    <tr>
                                        <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($movement->type == 'entree')
                                                <span class="badge badge-success">Entrée</span>
                                            @else
                                                <span class="badge badge-danger">Sortie</span>
                                            @endif
                                        </td>
                                        <td>{{ $movement->warehouse->nom }}</td>
                                        <td>{{ $movement->product->nom }}</td>
                                        <td class="text-right">{{ number_format($movement->quantite, 2) }}</td>
                                        <td class="text-right">{{ number_format($movement->prix_unitaire, 2) }}</td>
                                        <td class="text-right">{{ number_format($movement->montant_total, 2) }}</td>
                                        <td>{{ $movement->motif }}</td>
                                        <td>{{ $movement->createdBy->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Aucun mouvement trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total:</th>
                                    <th class="text-right">
                                        {{ number_format($movements->sum(function($movement) {
                                            return $movement->type == 'entree' ? $movement->quantite : -$movement->quantite;
                                        }), 2) }}
                                    </th>
                                    <th></th>
                                    <th class="text-right">
                                        {{ number_format($movements->sum(function($movement) {
                                            return $movement->type == 'entree' ? $movement->montant_total : -$movement->montant_total;
                                        }), 2) }}
                                    </th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $movements->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('export-excel').addEventListener('click', function(e) {
        e.preventDefault();
        let url = '{{ route("stock.reports.movements-history") }}' + '?export=excel';
        
        // Ajouter les paramètres de filtrage actuels
        const warehouseId = '{{ request("warehouse_id") }}';
        const productId = '{{ request("product_id") }}';
        const type = '{{ request("type") }}';
        const dateFrom = '{{ request("date_from") }}';
        const dateTo = '{{ request("date_to") }}';
        
        if (warehouseId) url += '&warehouse_id=' + warehouseId;
        if (productId) url += '&product_id=' + productId;
        if (type) url += '&type=' + type;
        if (dateFrom) url += '&date_from=' + dateFrom;
        if (dateTo) url += '&date_to=' + dateTo;
        
        window.location.href = url;
    });
</script>
@endpush