@extends('fournisseurs.portal.layout')

@section('title', 'Détail de la commande')

@section('header', 'Détail de la commande')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Commande #{{ $order->code }}</h2>
            <span class="inline-flex rounded-full bg-{{ $order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : ($order->statut === 'approved' ? 'blue' : 'red')) }}-100 px-3 py-1 text-sm font-semibold leading-5 text-{{ $order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : ($order->statut === 'approved' ? 'blue' : 'red')) }}-800">
                {{ ucfirst($order->statut) }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informations de la commande</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Date:</span> {{ $order->date_commande->format('d/m/Y') }}</p>
                    <p><span class="font-medium">Code:</span> {{ $order->code }}</p>
                    <p><span class="font-medium">Agence:</span> {{ $order->agency?->nom ?? 'N/A' }}</p>
                    <p><span class="font-medium">Fournisseur:</span> {{ $order->fournisseur?->raison_sociale ?? 'N/A' }}</p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Montants</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Montant HT:</span> {{ number_format($order->montant_ht, 0, ',', ' ') }} FCFA</p>
                    <p><span class="font-medium">TVA:</span> {{ number_format($order->tva, 0, ',', ' ') }} FCFA</p>
                    <p><span class="font-medium">Montant TTC:</span> {{ number_format($order->montant_ttc, 0, ',', ' ') }} FCFA</p>
                    <p><span class="font-medium">Montant payé:</span> {{ number_format($order->invoices->sum(function($invoice) { return $invoice->payments->sum('montant'); }), 0, ',', ' ') }} FCFA</p>
                    <p><span class="font-medium">Montant restant:</span> {{ number_format($order->montant_ttc - $order->invoices->sum(function($invoice) { return $invoice->payments->sum('montant'); }), 0, ',', ' ') }} FCFA</p>
                </div>
            </div>
        </div>
        
        <h3 class="text-lg font-semibold text-gray-700 mb-3">Articles de la commande</h3>
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
                    @forelse($order->items as $item)
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
        
        @if($order->deliveries && $order->deliveries->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Livraisons associées</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-primary-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Quantité livrée</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($order->deliveries as $delivery)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $delivery->code }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $delivery->date_livraison->format('d/m/Y') }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span class="inline-flex rounded-full bg-{{ $delivery->statut === 'completed' ? 'green' : ($delivery->statut === 'pending' ? 'yellow' : 'red') }}-100 px-2 text-xs font-semibold leading-5 text-{{ $delivery->statut === 'completed' ? 'green' : ($delivery->statut === 'pending' ? 'yellow' : 'red') }}-800">
                                            {{ ucfirst($delivery->statut) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $delivery->items->sum('quantite') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        
        @if($order->invoices && $order->invoices->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Factures associées</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-primary-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Numéro</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($order->invoices as $invoice)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $invoice->numero_facture }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $invoice->date_facture->format('d/m/Y') }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span class="inline-flex rounded-full bg-{{ $invoice->statut === 'paid' ? 'green' : ($invoice->statut === 'pending' ? 'yellow' : 'red') }}-100 px-2 text-xs font-semibold leading-5 text-{{ $invoice->statut === 'paid' ? 'green' : ($invoice->statut === 'pending' ? 'yellow' : 'red') }}-800">
                                            {{ ucfirst($invoice->statut) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ number_format($invoice->montant, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        
        <div class="mt-6 flex justify-end">
            <a href="{{ route('supplier.portal.orders') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection