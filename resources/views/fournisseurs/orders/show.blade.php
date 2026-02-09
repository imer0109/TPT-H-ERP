@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Bon de commande {{ $order->code }}</h1>
        <div class="flex gap-2">
            <button onclick="window.print()" class="btn btn-sm">Imprimer</button>
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <div><span class="text-gray-500">Fournisseur:</span> {{ $order->fournisseur->raison_sociale ?? '-' }}</div>
                <div><span class="text-gray-500">Date:</span> {{ $order->date_commande }}</div>
            </div>
            <div>
                <div><span class="text-gray-500">Statut:</span> {{ $order->statut }}</div>
                <div><span class="text-gray-500">Devise:</span> {{ $order->devise }}</div>
            </div>
        </div>

        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="text-left p-2">Produit</th>
                    <th class="text-left p-2">Désignation</th>
                    <th class="text-right p-2">Quantité</th>
                    <th class="text-left p-2">Unité</th>
                    <th class="text-right p-2">Prix</th>
                    <th class="text-right p-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $it)
                    <tr class="border-t">
                        <td class="p-2">{{ $it->product->name ?? '-' }}</td>
                        <td class="p-2">{{ $it->designation }}</td>
                        <td class="p-2 text-right">{{ number_format($it->quantite, 3, ',', ' ') }}</td>
                        <td class="p-2">{{ $it->unite }}</td>
                        <td class="p-2 text-right">{{ number_format($it->prix_unitaire, 2, ',', ' ') }}</td>
                        <td class="p-2 text-right">{{ number_format($it->montant_total, 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4"></th>
                    <th class="p-2 text-right">Total TTC</th>
                    <th class="p-2 text-right">{{ number_format($order->montant_ttc, 2, ',', ' ') }} {{ $order->devise }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('fournisseurs.orders.index') }}" class="text-primary-600">Retour</a>
    </div>
</div>
@endsection


