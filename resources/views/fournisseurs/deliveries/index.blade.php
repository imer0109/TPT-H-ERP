@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Livraisons fournisseurs</h1>
        <a href="{{ route('fournisseurs.deliveries.create') }}" class="btn btn-primary">Nouvelle livraison</a>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="p-2 text-left">Date</th>
                    <th class="p-2 text-left">Fournisseur</th>
                    <th class="p-2 text-left">Dépôt</th>
                    <th class="p-2 text-left">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $d)
                    <tr class="border-t">
                        <td class="p-2">{{ $d->date_reception }}</td>
                        <td class="p-2">{{ $d->fournisseur->raison_sociale ?? '-' }}</td>
                        <td class="p-2">{{ $d->warehouse->nom ?? '-' }}</td>
                        <td class="p-2">
                            <span class="px-2 py-1 text-xs rounded
                                @if($d->statut == 'received') bg-green-100 text-green-800
                                @elseif($d->statut == 'partial') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($d->statut) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-4 text-center text-gray-500">Aucune livraison</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $deliveries->links() }}</div>
    </div>
    <div class="mt-4">
        <a href="{{ route('fournisseurs.orders.index') }}" class="text-primary-600">Voir commandes</a>
    </div>
</div>
@endsection


