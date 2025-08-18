@extends('layouts.app')

@section('title', 'État Actuel du Stock')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">État Actuel du Stock</h3>
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
                    <form action="{{ route('stock.reports.current-stock') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Recherche</label>
                                    <input type="text" name="search" class="form-control" placeholder="Nom, référence ou description" value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary form-control">Filtrer</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Produit</th>
                                    <th>Catégorie</th>
                                    <th>Stock Actuel</th>
                                    <th>Prix d'Achat</th>
                                    <th>Valeur Stock</th>
                                    <th>Seuil d'Alerte</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    @php
                                        $stockActuel = request('warehouse_id') 
                                            ? $product->getStockInWarehouse(request('warehouse_id')) 
                                            : $product->stock_actuel;
                                            
                                        $valeurStock = $stockActuel * $product->prix_achat;
                                        
                                        $seuilAlerte = request('warehouse_id')
                                            ? ($product->stockAlerts->where('warehouse_id', request('warehouse_id'))->first()?->seuil_min ?? '-')
                                            : $product->seuil_alerte;
                                    @endphp
                                    <tr>
                                        <td>{{ $product->reference }}</td>
                                        <td>{{ $product->nom }}</td>
                                        <td>{{ $product->category->nom ?? 'Non catégorisé' }}</td>
                                        <td class="text-right">{{ number_format($stockActuel, 2) }}</td>
                                        <td class="text-right">{{ number_format($product->prix_achat, 2) }}</td>
                                        <td class="text-right">{{ number_format($valeurStock, 2) }}</td>
                                        <td class="text-right">{{ $seuilAlerte != '-' ? number_format($seuilAlerte, 2) : '-' }}</td>
                                        <td>
                                            @if($stockActuel <= 0)
                                                <span class="badge badge-danger">Rupture</span>
                                            @elseif($seuilAlerte != '-' && $stockActuel <= $seuilAlerte)
                                                <span class="badge badge-warning">Alerte</span>
                                            @else
                                                <span class="badge badge-success">Normal</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Aucun produit trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Valeur Totale du Stock:</th>
                                    <th class="text-right">
                                        {{ number_format($products->sum(function($product) use ($request) {
                                            $stockActuel = $request->warehouse_id 
                                                ? $product->getStockInWarehouse($request->warehouse_id) 
                                                : $product->stock_actuel;
                                            return $stockActuel * $product->prix_achat;
                                        }), 2) }}
                                    </th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $products->appends(request()->query())->links() }}
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
        let url = '{{ route("stock.reports.current-stock") }}' + '?export=excel';
        
        // Ajouter les paramètres de filtrage actuels
        const warehouseId = '{{ request("warehouse_id") }}';
        const search = '{{ request("search") }}';
        
        if (warehouseId) {
            url += '&warehouse_id=' + warehouseId;
        }
        
        if (search) {
            url += '&search=' + search;
        }
        
        window.location.href = url;
    });
</script>
@endpush