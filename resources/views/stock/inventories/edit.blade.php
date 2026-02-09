@extends('layouts.app')

@section('title', 'Modifier l\'Inventaire')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Modifier l'Inventaire #{{ $inventory->reference }}
        </h1>

        <a href="{{ route('stock.inventories.show', $inventory) }}"
           class="mt-3 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
            ← Retour
        </a>
    </div>

    <form action="{{ route('stock.inventories.update', $inventory) }}" method="POST"
          class="bg-white shadow rounded-lg">
        @csrf
        @method('PUT')

        <!-- Info box -->
        <div class="p-6 border-b">
            <div class="bg-primary-50 border border-primary-200 text-primary-700 rounded-lg p-4">
                <h4 class="font-semibold mb-1">Information</h4>
                <p class="text-sm">
                    Veuillez saisir les quantités réelles constatées lors de l'inventaire physique.
                    Les différences seront automatiquement calculées.
                </p>
            </div>
        </div>

        <!-- Inventory info -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
            <div>
                <span class="font-semibold">Référence :</span> {{ $inventory->reference }}
            </div>
            <div>
                <span class="font-semibold">Date :</span> {{ $inventory->date->format('d/m/Y') }}
            </div>
            <div>
                <span class="font-semibold">Dépôt :</span> {{ $inventory->warehouse->nom }}
            </div>
        </div>

        <!-- Notes -->
        <div class="px-6 pb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="3"
                class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-primary-300 @error('notes') border-red-500 @enderror">{{ old('notes', $inventory->notes) }}</textarea>
            @error('notes')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Table -->
        <div class="overflow-x-auto px-6 pb-6">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-100">
                    <tr class="text-sm text-gray-700">
                        <th class="px-4 py-3 text-left">Produit</th>
                        <th class="px-4 py-3 text-left">Référence</th>
                        <th class="px-4 py-3 text-right">Stock Théorique</th>
                        <th class="px-4 py-3 text-right">Stock Réel</th>
                        <th class="px-4 py-3 text-right">Différence</th>
                        <th class="px-4 py-3 text-left">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y">

                    @foreach($inventory->items as $item)
                    <tr class="hover:bg-primary-50 text-sm">
                        <td class="px-4 py-2">{{ $item->product->name }}</td>
                        <td class="px-4 py-2">{{ $item->product->reference }}</td>

                        <td class="px-4 py-2 text-right">
                            {{ number_format($item->theoretical_quantity, 2, ',', ' ') }}
                        </td>

                        <td class="px-4 py-2">
                            <input type="number" step="0.01"
                                name="items[{{ $item->id }}][actual_quantity]"
                                value="{{ old('items.'.$item->id.'.actual_quantity', $item->actual_quantity) }}"
                                class="w-full border rounded px-2 py-1 text-right focus:ring focus:ring-primary-300">
                        </td>

                        <td class="px-4 py-2 text-right">
                            @if($item->difference !== null)
                                <span class="{{ $item->difference < 0 ? 'text-red-600' : ($item->difference > 0 ? 'text-green-600' : '') }}">
                                    {{ number_format($item->difference, 2, ',', ' ') }}
                                </span>
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-4 py-2">
                            <input type="text"
                                name="items[{{ $item->id }}][notes]"
                                value="{{ old('items.'.$item->id.'.notes', $item->notes) }}"
                                class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t flex flex-col sm:flex-row gap-3">
            <button type="submit"
                class="inline-flex items-center justify-center px-5 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
                💾 Enregistrer les modifications
            </button>

            <!-- Formulaire de validation de l'inventaire -->
            <form action="{{ route('stock.inventories.validate', $inventory) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center justify-center px-5 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                        onclick="return confirm('Êtes-vous sûr de vouloir valider cet inventaire ? Cette action est irréversible.')">
                    ✔ Valider l’inventaire
                </button>
            </form>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('tbody tr').forEach(row => {
        const actualInput = row.querySelector('input[type="number"]');
        const theoreticalCell = row.querySelector('td:nth-child(3)');
        const differenceCell = row.querySelector('td:nth-child(5)');

        actualInput.addEventListener('input', function () {
            const theoretical = parseFloat(theoreticalCell.textContent.replace(/[^\d.-]/g, ''));
            const actual = parseFloat(this.value) || 0;
            const difference = actual - theoretical;

            if (!isNaN(difference)) {
                differenceCell.innerHTML =
                    `<span class="${difference < 0 ? 'text-red-600' : (difference > 0 ? 'text-green-600' : '')}">
                        ${difference.toFixed(2)}
                    </span>`;
            } else {
                differenceCell.innerHTML = '-';
            }
        });
    });
});
</script>
@endpush