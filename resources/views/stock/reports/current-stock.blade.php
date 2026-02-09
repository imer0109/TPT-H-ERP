@extends('layouts.app')

@section('title', 'État Actuel du Stock')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">État Actuel du Stock</h1>
        <div class="flex space-x-2">
            <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center space-x-2">
                <i class="fas fa-print"></i><span>Imprimer</span>
            </button>
            <button id="export-excel" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded flex items-center space-x-2">
                <i class="fas fa-file-excel"></i><span>Exporter Excel</span>
            </button>
        </div>
    </div>

    {{-- Filtres --}}
    <form action="{{ route('stock.reports.current-stock') }}" method="GET" class="bg-white shadow rounded-lg p-6 mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dépôt</label>
            <select name="warehouse_id" class="block w-full border border-gray-300 rounded-md p-2">
                <option value="">Tous les dépôts</option>
                @foreach($warehouses as $id => $name)
                    <option value="{{ $id }}" {{ request('warehouse_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
            <input type="text" name="search" placeholder="Nom, référence ou description" value="{{ request('search') }}"
                   class="block w-full border border-gray-300 rounded-md p-2">
        </div>

        <div class="flex items-end">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded w-full">Filtrer</button>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase">Référence</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase">Produit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase">Catégorie</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-primary-700 uppercase">Stock Actuel</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-primary-700 uppercase">Prix d'Achat</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-primary-700 uppercase">Valeur Stock</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-primary-700 uppercase">Seuil d'Alerte</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase">Statut</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                    @php
                        $stockActuel = request('warehouse_id') 
                            ? $product->getStockInWarehouse(request('warehouse_id')) 
                            : $product->quantite;
                        $valeurStock = $stockActuel * $product->prix_unitaire;
                        $seuilAlerte = request('warehouse_id')
                            ? ($product->stockAlerts->where('warehouse_id', request('warehouse_id'))->first()?->seuil_min ?? '-')
                            : $product->seuil_alerte;
                    @endphp
                    <tr>
                        <td class="px-6 py-3 text-sm text-gray-900">{{ $product->reference }}</td>
                        <td class="px-6 py-3 text-sm text-gray-900">{{ $product->name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-900">{{ $product->category->name ?? 'Non catégorisé' }}</td>
                        <td class="px-6 py-3 text-right text-sm">{{ number_format($stockActuel, 2) }}</td>
                        <td class="px-6 py-3 text-right text-sm">{{ number_format($product->prix_unitaire, 2) }}</td>
                        <td class="px-6 py-3 text-right text-sm">{{ number_format($valeurStock, 2) }}</td>
                        <td class="px-6 py-3 text-right text-sm">{{ $seuilAlerte != '-' ? number_format($seuilAlerte, 2) : '-' }}</td>
                        <td class="px-6 py-3">
                            @if($stockActuel <= 0)
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">Rupture</span>
                            @elseif($seuilAlerte != '-' && $stockActuel <= $seuilAlerte)
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">Alerte</span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">Normal</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Aucun produit trouvé</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <th colspan="5" class="px-6 py-3 text-right text-sm font-medium">Valeur Totale du Stock:</th>
                    <th class="px-6 py-3 text-right text-sm font-medium">
                        {{ number_format($products->sum(function($product) {
                            $wid = request('warehouse_id');
                            $stockActuel = $wid
                                ? $product->getStockInWarehouse($wid)
                                : $product->quantite;
                            return $stockActuel * $product->prix_unitaire;
                        }), 2) }}
                    </th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('export-excel').addEventListener('click', function(e) {
        e.preventDefault();
        let url = '{{ route("stock.reports.current-stock") }}' + '?export=excel';
        const warehouseId = '{{ request("warehouse_id") }}';
        const search = '{{ request("search") }}';
        if (warehouseId) url += '&warehouse_id=' + warehouseId;
        if (search) url += '&search=' + search;
        window.location.href = url;
    });
</script>
@endpush
