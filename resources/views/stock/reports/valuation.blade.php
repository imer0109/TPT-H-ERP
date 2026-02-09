@extends('layouts.app')

@section('title', 'Valorisation du Stock')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Valorisation du Stock</h3>
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
                    <form action="{{ route('stock.reports.valuation') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary form-control">Filtrer</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Information</h5>
                        <p>
                            @if(request('warehouse_id'))
                                Valorisation du stock pour le dépôt: <strong>{{ $warehouses[request('warehouse_id')] }}</strong>
                            @else
                                Valorisation du stock pour tous les dépôts
                            @endif
                        </p>
                        <p>Valeur totale du stock: <strong>{{ number_format($totalValuation, 2) }} FCFA</strong></p>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Produit</th>
                                    <th>Prix d'Achat</th>
                                    <th>Quantité en Stock</th>
                                    <th>Valeur du Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $product->reference }}</td>
                                        <td>{{ $product->nom }}</td>
                                        <td>{{ $product->category->name ?? 'Non catégorisé' }}</td>
                                        <td class="text-right">{{ number_format($product->prix_achat, 2) }}</td>
                                        <td class="text-right">{{ number_format($product->stock_actuel, 2) }}</td>
                                        <td class="text-right">{{ number_format($product->valeur_stock, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucun produit trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Valeur Totale du Stock:</th>
                                    <th class="text-right">{{ number_format($totalValuation, 2) }}</th>
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
        let url = '{{ route("stock.reports.valuation") }}' + '?export=excel';
        
        // Ajouter les paramètres de filtrage actuels
        const warehouseId = '{{ request("warehouse_id") }}';
        
        if (warehouseId) {
            url += '&warehouse_id=' + warehouseId;
        }
        
        window.location.href = url;
    });
</script>
@endpush