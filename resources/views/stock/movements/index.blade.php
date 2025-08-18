@extends('layouts.app')

@section('title', 'Mouvements de Stock')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Mouvements de Stock</h1>
        <div class="flex space-x-3">
            <a href="{{ route('stock.movements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nouveau Mouvement
            </a>
            <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Importer
            </button>
            <a href="{{ route('stock.movements.export') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Exporter
            </a>
        </div>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filtres --}}
    <form action="{{ route('stock.movements.index') }}" method="GET" class="bg-white shadow sm:rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Type de mouvement</label>
                <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Tous</option>
                    <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrée</option>
                    <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                </select>
            </div>
            <div>
                <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Dépôt</label>
                <select name="warehouse_id" id="warehouse_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Tous</option>
                    @foreach($warehouses as $id => $name)
                        <option value="{{ $id }}" {{ request('warehouse_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_start" class="block text-sm font-medium text-gray-700">Date début</label>
                <input type="date" name="date_start" id="date_start" value="{{ request('date_start') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="date_end" class="block text-sm font-medium text-gray-700">Date fin</label>
                <input type="date" name="date_end" id="date_end" value="{{ request('date_end') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
        </div>
        <div class="mt-4 flex justify-end space-x-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Filtrer</button>
            <a href="{{ route('stock.movements.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Réinitialiser</a>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Produit</th>
                    <th class="px-6 py-3">Dépôt</th>
                    <th class="px-6 py-3">Quantité</th>
                    <th class="px-6 py-3">Prix Unitaire</th>
                    <th class="px-6 py-3">Montant Total</th>
                    <th class="px-6 py-3">Statut</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($movements as $movement)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $movement->reference }}</td>
                        <td class="px-6 py-4 text-sm">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $movement->type == 'entree' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($movement->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $movement->product->nom ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $movement->warehouse->nom ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $movement->quantite }}</td>
                        <td class="px-6 py-4 text-sm">{{ number_format($movement->prix_unitaire, 2) }}</td>
                        <td class="px-6 py-4 text-sm">{{ number_format($movement->montant_total, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $movement->validated_by ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $movement->validated_by ? 'Validé' : 'En attente' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium flex space-x-2">
                            <a href="{{ route('stock.movements.show', $movement) }}" class="text-blue-600 hover:text-blue-900">Détails</a>
                            @if(!$movement->validated_by && auth()->user()->can('validate', $movement))
                                <form action="{{ route('stock.movements.validate', $movement) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir valider ce mouvement ?')">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">Valider</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">Aucun mouvement de stock trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $movements->appends(request()->query())->links() }}
    </div>
</div>

{{-- Modal Import (version Tailwind) --}}
<div id="importModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h2 class="text-lg font-semibold mb-4">Importer des Mouvements de Stock</h2>
        <form action="{{ route('stock.movements.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700">Fichier Excel</label>
                <input type="file" name="file" id="file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: .xlsx, .xls</p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Annuler
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Importer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
