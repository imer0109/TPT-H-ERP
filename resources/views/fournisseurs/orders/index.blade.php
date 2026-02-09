@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Commandes fournisseurs</h1>
        <a href="{{ route('fournisseurs.orders.create') }}" class="btn btn-primary">Nouvelle commande</a>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="text-left p-2">Code</th>
                    <th class="text-left p-2">Fournisseur</th>
                    <th class="text-left p-2">Date</th>
                    <th class="text-left p-2">Statut</th>
                    <th class="text-right p-2">Montant</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-t">
                        <td class="p-2">{{ $order->code }}</td>
                        <td class="p-2">{{ $order->fournisseur->raison_sociale ?? '-' }}</td>
                        <td class="p-2">{{ $order->date_commande }}</td>
                        <td class="p-2">{{ $order->statut }}</td>
                        <td class="p-2 text-right">{{ number_format($order->montant_ttc, 2, ',', ' ') }} {{ $order->devise }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-4 text-center text-gray-500">Aucune commande</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $orders->links() }}</div>
    </div>
    <div class="mt-4 text-sm text-gray-500">Liens: achats/stock, import PDF/Excel (à implémenter)</div>
    <div class="mt-2">
        <a href="{{ route('fournisseurs.index') }}" class="text-primary-600">Retour fournisseurs</a>
    </div>
    <div class="mt-4 flex gap-3">
        <a href="{{ route('fournisseurs.deliveries.index') }}" class="underline">Livraisons</a>
        <a href="{{ route('fournisseurs.payments.index') }}" class="underline">Paiements</a>
        <a href="{{ route('fournisseurs.issues.index') }}" class="underline">Réclamations</a>
    </div>
@endsection


