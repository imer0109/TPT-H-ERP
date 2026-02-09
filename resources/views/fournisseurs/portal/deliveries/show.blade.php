@extends('fournisseurs.portal.layout')

@section('title', 'Détail de la livraison')

@section('header', 'Détail de la livraison')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Livraison #{{ $delivery->code }}</h2>
            <span class="inline-flex rounded-full bg-{{ $delivery->statut === 'completed' ? 'green' : ($delivery->statut === 'pending' ? 'yellow' : 'red') }}-100 px-3 py-1 text-sm font-semibold leading-5 text-{{ $delivery->statut === 'completed' ? 'green' : ($delivery->statut === 'pending' ? 'yellow' : 'red') }}-800">
                {{ ucfirst($delivery->statut) }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informations de la livraison</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Date de livraison:</span> {{ $delivery->date_livraison->format('d/m/Y') }}</p>
                    <p><span class="font-medium">Code:</span> {{ $delivery->code }}</p>
                    <p><span class="font-medium">Commande:</span> {{ $delivery->order?->code ?? 'N/A' }}</p>
                    <p><span class="font-medium">Fournisseur:</span> {{ $delivery->fournisseur?->raison_sociale ?? 'N/A' }}</p>
                    <p><span class="font-medium">Entrepôt:</span> {{ $delivery->warehouse?->nom ?? 'N/A' }}</p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Détails supplémentaires</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Statut:</span> {{ ucfirst($delivery->statut) }}</p>
                    <p><span class="font-medium">Quantité totale:</span> {{ $delivery->items->sum('quantite') }}</p>
                    <p><span class="font-medium">Date de création:</span> {{ $delivery->created_at->format('d/m/Y H:i') }}</p>
                    @if($delivery->notes)
                        <p><span class="font-medium">Notes:</span> {{ $delivery->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <h3 class="text-lg font-semibold text-gray-700 mb-3">Articles livrés</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Prix unitaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($delivery->items as $item)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $item->product?->libelle ?? 'N/A' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $item->quantite }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ number_format($item->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ number_format($item->total, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Aucun article trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($delivery->order)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Commande associée</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><span class="font-medium">Code commande:</span> <a href="{{ route('supplier.portal.orders.show', $delivery->order) }}" class="text-primary-600 hover:text-primary-800">{{ $delivery->order->code }}</a></p>
                            <p><span class="font-medium">Date commande:</span> {{ $delivery->order->date_commande->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Statut commande:</span> {{ ucfirst($delivery->order->statut) }}</p>
                            <p><span class="font-medium">Montant TTC:</span> {{ number_format($delivery->order->montant_ttc, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="mt-6 flex justify-end">
            <a href="{{ route('supplier.portal.deliveries') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection