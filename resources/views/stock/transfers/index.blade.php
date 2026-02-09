@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center bg-gradient-to-r from-primary-600 to-indigo-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-exchange-alt mr-2"></i> Transferts de Stock
            </h2>
            <a href="{{ route('stock.transfers.create') }}" 
               class="bg-white text-primary-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 shadow">
                <i class="fas fa-plus mr-1"></i> Nouveau Transfert
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-gray-600 font-semibold">N° Transfert</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Date</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Dépôt Source</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Dépôt Destination</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Produit</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Unité</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Quantité</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Statut</th>
                        <th class="px-6 py-3 text-center text-gray-600 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transfers as $transfer)
                    <tr class="hover:bg-primary-50">
                        <td class="px-6 py-4 font-medium">#{{ $transfer->numero_transfert }}</td>
                        <td class="px-6 py-4">{{ $transfer->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">{{ $transfer->warehouseSource->nom }}</td>
                        <td class="px-6 py-4">{{ $transfer->warehouseDestination->nom }}</td>
                        <td class="px-6 py-4">{{ $transfer->product->name }}</td>
                        <td class="px-6 py-4">{{ $transfer->unite }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-sm">
                                {{ number_format($transfer->quantite, 0, ',', ' ') }} 
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $transfer->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $transfer->statut === 'en_transit' ? 'bg-primary-100 text-primary-700' : '' }}
                                {{ $transfer->statut === 'receptionne' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $transfer->statut === 'annule' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $transfer->statut)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <!-- Voir -->
                            <a href="{{ route('stock.transfers.show', $transfer) }}" 
                               class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-100 text-primary-600 hover:bg-primary-200" 
                               title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Valider -->
                            @if($transfer->statut === 'en_attente')
                            <form action="{{ route('stock.transfers.validate', $transfer) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 hover:bg-green-200"
                                    title="Valider transfert">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif

                            <!-- Réception -->
                            @if($transfer->statut === 'en_transit')
                            <form action="{{ route('stock.transfers.receive', $transfer) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 hover:bg-indigo-200"
                                    title="Marquer comme reçu">
                                    <i class="fas fa-inbox"></i>
                                </button>
                            </form>
                            @endif

                            <!-- Annuler -->
                            @if(in_array($transfer->statut, ['en_attente', 'en_transit']))
                            <form action="{{ route('stock.transfers.cancel', $transfer) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir annuler ce transfert ?')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200"
                                    title="Annuler transfert">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Aucun transfert trouvé.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $transfers->links() }}
        </div>
    </div>
</div>
@endsection
