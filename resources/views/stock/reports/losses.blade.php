@extends('layouts.app')

@section('title', 'Rapport des Pertes de Stock')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rapport des Pertes de Stock</h3>
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
                    <form action="{{ route('stock.reports.losses') }}" method="GET" class="mb-4">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date début</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date fin</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary form-control">Filtrer</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Résumé des Pertes</h5>
                        <p>
                            @if(request('warehouse_id'))
                                Pertes pour le dépôt: <strong>{{ $warehouses[request('warehouse_id')] }}</strong>
                            @else
                                Pertes pour tous les dépôts
                            @endif
                        </p>
                        <p>Période: 
                            <strong>
                                {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') : 'Début' }} 
                                à 
                                {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') : 'Aujourd\'hui' }}
                            </strong>
                        </p>
                        <p>Valeur totale des pertes: <strong>{{ number_format($totalLoss, 2) }} FCFA</strong></p>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Dépôt</th>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Montant Total</th>
                                    <th>Motif</th>
                                    <th>Enregistré par</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($losses as $loss)
                                    <tr>
                                        <td>{{ $loss->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $loss->warehouse->nom }}</td>
                                        <td>{{ $loss->product->nom }}</td>
                                        <td class="text-right">{{ number_format($loss->quantite, 2) }}</td>
                                        <td class="text-right">{{ number_format($loss->prix_unitaire, 2) }}</td>
                                        <td class="text-right">{{ number_format($loss->montant_total, 2) }}</td>
                                        <td>{{ $loss->motif }}</td>
                                        <td>{{ $loss->createdBy->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Aucune perte enregistrée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Total des Pertes:</th>
                                    <th class="text-right">{{ number_format($totalLoss, 2) }}</th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $losses->appends(request()->query())->links() }}
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
        let url = '{{ route("stock.reports.losses") }}' + '?export=excel';
        
        // Ajouter les paramètres de filtrage actuels
        const warehouseId = '{{ request("warehouse_id") }}';
        const dateFrom = '{{ request("date_from") }}';
        const dateTo = '{{ request("date_to") }}';
        
        if (warehouseId) url += '&warehouse_id=' + warehouseId;
        if (dateFrom) url += '&date_from=' + dateFrom;
        if (dateTo) url += '&date_to=' + dateTo;
        
        window.location.href = url;
    });
</script>
@endpush