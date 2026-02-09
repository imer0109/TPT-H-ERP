@extends('fournisseurs.portal.layout')

@section('title', 'Détail du paiement')

@section('header', 'Détail du paiement')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Paiement #{{ $payment->id }}</h2>
            <span class="inline-flex rounded-full bg-{{ $payment->statut === 'validated' ? 'green' : ($payment->statut === 'pending' ? 'yellow' : 'red') }}-100 px-3 py-1 text-sm font-semibold leading-5 text-{{ $payment->statut === 'validated' ? 'green' : ($payment->statut === 'pending' ? 'yellow' : 'red') }}-800">
                {{ ucfirst($payment->statut) }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informations du paiement</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Date:</span> {{ $payment->date_paiement->format('d/m/Y') }}</p>
                    <p><span class="font-medium">Mode de paiement:</span> {{ ucfirst($payment->mode_paiement) }}</p>
                    <p><span class="font-medium">Montant:</span> {{ number_format($payment->montant, 0, ',', ' ') }} FCFA</p>
                    <p><span class="font-medium">Fournisseur:</span> {{ $payment->fournisseur?->raison_sociale ?? 'N/A' }}</p>
                    <p><span class="font-medium">Validé par:</span> {{ $payment->validatedBy?->name ?? 'N/A' }}</p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Détails du paiement</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Statut:</span> {{ ucfirst($payment->statut) }}</p>
                    <p><span class="font-medium">Date de création:</span> {{ $payment->created_at->format('d/m/Y H:i') }}</p>
                    @if($payment->reference)
                        <p><span class="font-medium">Référence:</span> {{ $payment->reference }}</p>
                    @endif
                    @if($payment->notes)
                        <p><span class="font-medium">Notes:</span> {{ $payment->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        @if($payment->invoice)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Facture associée</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><span class="font-medium">Numéro facture:</span> <a href="{{ route('supplier.portal.invoices.show', $payment->invoice) }}" class="text-primary-600 hover:text-primary-800">{{ $payment->invoice->numero_facture }}</a></p>
                            <p><span class="font-medium">Date facture:</span> {{ $payment->invoice->date_facture->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Statut facture:</span> {{ ucfirst($payment->invoice->statut) }}</p>
                            <p><span class="font-medium">Montant facture:</span> {{ number_format($payment->invoice->montant_ttc, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        @if($payment->invoice && $payment->invoice->order)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Commande associée</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><span class="font-medium">Code commande:</span> <a href="{{ route('supplier.portal.orders.show', $payment->invoice->order) }}" class="text-primary-600 hover:text-primary-800">{{ $payment->invoice->order->code }}</a></p>
                            <p><span class="font-medium">Date commande:</span> {{ $payment->invoice->order->date_commande->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Statut commande:</span> {{ ucfirst($payment->invoice->order->statut) }}</p>
                            <p><span class="font-medium">Montant commande:</span> {{ number_format($payment->invoice->order->montant_ttc, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="mt-6 flex justify-end">
            <a href="{{ route('supplier.portal.payments') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection