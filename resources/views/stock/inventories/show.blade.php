@extends('layouts.app')

@section('title', 'Détails de l\'Inventaire')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h3 class="text-xl font-semibold">Détails de l'Inventaire #{{ $inventory->reference }}</h3>
        <div class="space-x-2">
            <a href="{{ route('stock.inventories.index') }}" 
               class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-1"></i> Retour
            </a>
            <button onclick="window.print()" 
                    class="inline-flex items-center px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                <i class="fas fa-print mr-1"></i> Imprimer
            </button>
            <a href="{{ route('stock.inventories.pdf', $inventory) }}" 
               class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
        </div>
    </div>

    <!-- Informations Générales & Complémentaires -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Informations Générales -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h4 class="font-semibold text-gray-700">Informations Générales</h4>
            </div>
            <div class="px-6 py-4">
                <table class="min-w-full text-sm text-gray-700">
                    <tr class="border-b">
                        <th class="text-left w-1/3 py-1">Référence</th>
                        <td>{{ $inventory->reference }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-1">Date</th>
                        <td>{{ $inventory->date->format('d/m/Y') }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-1">Dépôt</th>
                        <td>{{ $inventory->warehouse->nom }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-1">Statut</th>
                        <td>
                            @if($inventory->status == 'en_cours')
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-200 rounded-full">En cours</span>
                            @elseif($inventory->status == 'valide')
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-200 rounded-full">Validé</span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-red-700 bg-red-200 rounded-full">Annulé</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left py-1">Notes</th>
                        <td>{{ $inventory->notes ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Informations Complémentaires -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h4 class="font-semibold text-gray-700">Informations Complémentaires</h4>
            </div>
            <div class="px-6 py-4">
                <table class="min-w-full text-sm text-gray-700">
                    <tr class="border-b">
                        <th class="text-left w-1/3 py-1">Créé par</th>
                        <td>{{ $inventory->createdBy->name }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-1">Date de création</th>
                        <td>{{ $inventory->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-1">Validé par</th>
                        <td>{{ $inventory->validatedBy?->name ?? 'Non validé' }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-1">Date de validation</th>
                        <td>{{ $inventory->validated_at ? $inventory->validated_at->format('d/m/Y H:i') : 'Non validé' }}</td>
                    </tr>
                    <tr>
                        <th class="text-left py-1">Nombre de produits</th>
                        <td>{{ $inventory->items->count() }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Détails de l'inventaire -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h4 class="font-semibold text-gray-700">Détails de l'Inventaire</h4>
            @if($inventory->status == 'en_cours')
                <a href="{{ route('stock.inventories.edit', $inventory) }}" 
                   class="inline-flex items-center px-3 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                    <i class="fas fa-edit mr-1"></i> Modifier
                </a>
            @endif
        </div>
        <div class="px-6 py-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-3 py-2 text-left">Produit</th>
                        <th class="px-3 py-2 text-left">Référence</th>
                        <th class="px-3 py-2 text-right">Stock Théorique</th>
                        <th class="px-3 py-2 text-right">Stock Réel</th>
                        <th class="px-3 py-2 text-right">Différence</th>
                        <th class="px-3 py-2 text-right">Valeur Différence</th>
                        <th class="px-3 py-2 text-left">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
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
                            <td class="px-3 py-2">{{ $item->product->name }}</td>
                            <td class="px-3 py-2">{{ $item->product->reference }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($item->theoretical_quantity, 2) }}</td>
                            <td class="px-3 py-2 text-right">{{ $item->actual_quantity !== null ? number_format($item->actual_quantity, 2) : '-' }}</td>
                            <td class="px-3 py-2 text-right">
                                @if($item->difference !== null)
                                    <span class="{{ $item->difference < 0 ? 'text-red-600' : ($item->difference > 0 ? 'text-green-600' : '') }}">
                                        {{ number_format($item->difference, 2) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-3 py-2 text-right">
                                @if($item->difference !== null)
                                    <span class="{{ $differenceValue < 0 ? 'text-red-600' : ($differenceValue > 0 ? 'text-green-600' : '') }}">
                                        {{ number_format($differenceValue, 2) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-3 py-2">{{ $item->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-semibold">
                    <tr>
                        <th colspan="4" class="text-right px-3 py-2">Total des Différences:</th>
                        <th class="text-right px-3 py-2 {{ $totalDifference < 0 ? 'text-red-600' : ($totalDifference > 0 ? 'text-green-600' : '') }}">
                            {{ number_format($totalDifference, 2) }}
                        </th>
                        <th class="text-right px-3 py-2 {{ $totalDifferenceValue < 0 ? 'text-red-600' : ($totalDifferenceValue > 0 ? 'text-green-600' : '') }}">
                            {{ number_format($totalDifferenceValue, 2) }}
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if($inventory->status == 'en_cours')
        <div class="text-center mt-4">
            <form action="{{ route('stock.inventories.validate', $inventory) }}" method="POST" class="inline-block">
                @csrf
                <!-- Suppression de @method('PATCH') car la route accepte POST -->
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                        onclick="return confirm('Êtes-vous sûr de vouloir valider cet inventaire? Cette action est irréversible.')">
                    <i class="fas fa-check mr- 1"></i> Valider l'Inventaire
                </button>
            </form>
        </div>
    @endif

</div>
@endsection
