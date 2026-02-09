@extends('fournisseurs.portal.layout')

@section('title', 'Détail de la facture')

@section('header', 'Détail de la facture')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Facture #{{ $invoice->numero_facture }}</h2>
            <span class="inline-flex rounded-full bg-{{ $invoice->statut === 'paid' ? 'green' : ($invoice->statut === 'pending' ? 'yellow' : 'red') }}-100 px-3 py-1 text-sm font-semibold leading-5 text-{{ $invoice->statut === 'paid' ? 'green' : ($invoice->statut === 'pending' ? 'yellow' : 'red') }}-800">
                {{ ucfirst($invoice->statut) }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informations de la facture</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Date:</span> {{ $invoice->date_facture->format('d/m/Y') }}</p>
                    <p><span class="font-medium">Numéro:</span> {{ $invoice->numero_facture }}</p>
                    <p><span class="font-medium">Commande:</span> {{ $invoice->order?->code ?? 'N/A' }}</p>
                    <p><span class="font-medium">Fournisseur:</span> {{ $invoice->fournisseur?->raison_sociale ?? 'N/A' }}</p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Montants</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Montant HT:</span> {{ number_format($invoice->montant_ht, 0, ',', ' ') }} FCFA</p>
                    <p><span class="font-medium">TVA:</span> {{ number_format($invoice->tva, 0, ',', ' ') }} FCFA</p>
                    <p><span class="font-medium">Montant TTC:</span> {{ number_format($invoice->montant_ttc, 0, ',', ' ') }} FCFA</p>
                    <p><span class="font-medium">Montant payé:</span> {{ number_format($invoice->paiements()->sum('montant'), 0, ',', ' ') }} FCFA</p>
                    <p><span class="font-medium">Montant restant:</span> {{ number_format($invoice->montant_ttc - $invoice->paiements()->sum('montant'), 0, ',', ' ') }} FCFA</p>
                </div>
            </div>
        </div>
        
        @if($invoice->order && $invoice->order->items->count() > 0)
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Articles de la commande associée</h3>
            <div class="overflow-x-auto mb-6">
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
                        @forelse($invoice->order->items as $item)
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
        @endif
        
        @if($invoice->payments && $invoice->payments->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Paiements associés</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-primary-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Mode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Montant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($invoice->payments as $payment)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $payment->date_paiement->format('d/m/Y') }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ ucfirst($payment->mode_paiement) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ number_format($payment->montant, 0, ',', ' ') }} FCFA</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span class="inline-flex rounded-full bg-{{ $payment->statut === 'validated' ? 'green' : ($payment->statut === 'pending' ? 'yellow' : 'red') }}-100 px-2 text-xs font-semibold leading-5 text-{{ $payment->statut === 'validated' ? 'green' : ($payment->statut === 'pending' ? 'yellow' : 'red') }}-800">
                                            {{ ucfirst($payment->statut) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        
        @if($invoice->order)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Commande associée</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><span class="font-medium">Code commande:</span> <a href="{{ route('supplier.portal.orders.show', $invoice->order) }}" class="text-primary-600 hover:text-primary-800">{{ $invoice->order->code }}</a></p>
                            <p><span class="font-medium">Date commande:</span> {{ $invoice->order->date_commande->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Statut commande:</span> {{ ucfirst($invoice->order->statut) }}</p>
                            <p><span class="font-medium">Montant TTC:</span> {{ number_format($invoice->order->montant_ttc, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="mt-6 flex justify-end">
            <a href="{{ route('supplier.portal.invoices') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection
