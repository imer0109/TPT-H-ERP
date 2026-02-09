@extends('layouts.app')

@section('content')
<div class="w-full px-4 py-6">
    <div class="bg-white shadow-md rounded-xl p-6">
        
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">
                Détails du Transfert #{{ $transfer->numero_transfert }}
            </h3>

            <a href="{{ route('stock.transfers.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                ← Retour
            </a>
        </div>

        {{-- Infos principales --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Tableau gauche --}}
            <div class="border rounded-lg overflow-hidden">
                <table class="w-full">
                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Statut</th>
                        <td class="p-3">
                            <span class="
                                px-3 py-1 rounded-full text-sm font-semibold
                                @if($transfer->statut === 'en_attente') bg-yellow-200 text-yellow-800 
                                @elseif($transfer->statut === 'en_transit') bg-primary-200 text-primary-800
                                @elseif($transfer->statut === 'receptionne') bg-green-200 text-green-800
                                @else bg-red-200 text-red-800
                                @endif
                            ">
                                {{ ucfirst(str_replace('_', ' ', $transfer->statut)) }}
                            </span>
                        </td>
                    </tr>

                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Dépôt Source</th>
                        <td class="p-3">{{ $transfer->warehouseSource->nom }}</td>
                    </tr>

                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Dépôt Destination</th>
                        <td class="p-3">{{ $transfer->warehouseDestination->nom }}</td>
                    </tr>

                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Produit</th>
                        <td class="p-3">{{ $transfer->product->name }}</td>
                    </tr>

                    <tr>
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Quantité</th>
                        <td class="p-3">{{ intval($transfer->quantite) }} {{ $transfer->unite }}</td>
                    </tr>
                </table>
            </div>

            {{-- Tableau droite --}}
            <div class="border rounded-lg overflow-hidden">
                <table class="w-full">
                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Créé par</th>
                        <td class="p-3">
                            {{ $transfer->createdBy->name }}  
                            <span class="text-gray-500 text-sm">
                                ({{ $transfer->created_at->format('d/m/Y H:i') }})
                            </span>
                        </td>
                    </tr>

                    @if($transfer->validated_by)
                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Validé par</th>
                        <td class="p-3">
                            {{ $transfer->validatedBy->name }}  
                            <span class="text-gray-500 text-sm">
                                ({{ $transfer->date_validation->format('d/m/Y H:i') }})
                            </span>
                        </td>
                    </tr>
                    @endif

                    @if($transfer->received_by)
                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Réceptionné par</th>
                        <td class="p-3">
                            {{ $transfer->receivedBy->name }}  
                            <span class="text-gray-500 text-sm">
                                ({{ $transfer->date_reception->format('d/m/Y H:i') }})
                            </span>
                        </td>
                    </tr>
                    @endif

                    @if($transfer->justificatif)
                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Justificatif</th>
                        <td class="p-3">
                            <a href="{{ Storage::url($transfer->justificatif) }}" 
                               target="_blank" 
                               class="px-3 py-1 bg-primary-600 text-white rounded-lg text-sm hover:bg-primary-700 transition">
                                Voir le document
                            </a>
                        </td>
                    </tr>
                    @endif

                    @if($transfer->notes)
                    <tr>
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Notes</th>
                        <td class="p-3">{{ $transfer->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Actions --}}
        <div class="text-center mt-8 space-x-3">
            @if($transfer->statut === 'en_attente')
            <form action="{{ route('stock.transfers.validate', $transfer) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                    class="px-5 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                    Valider le transfert
                </button>
            </form>
            @endif

            @if($transfer->statut === 'en_transit')
            <form action="{{ route('stock.transfers.receive', $transfer) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                    class="px-5 py-2 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition">
                    Réceptionner
                </button>
            </form>
            @endif

            @if(in_array($transfer->statut, ['en_attente', 'en_transit']))
            <form action="{{ route('stock.transfers.cancel', $transfer) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                    onclick="return confirm('Êtes-vous sûr de vouloir annuler ce transfert ?')"
                    class="px-5 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                    Annuler le transfert
                </button>
            </form>
            @endif
        </div>

    </div>
</div>
@endsection
