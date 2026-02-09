@extends('fournisseurs.portal.layout')

@section('title', 'Détail de la réclamation')

@section('header', 'Détail de la réclamation')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Réclamation #{{ $issue->id }}</h2>
            <span class="inline-flex rounded-full bg-{{ $issue->statut === 'resolved' ? 'green' : ($issue->statut === 'in_progress' ? 'blue' : ($issue->statut === 'open' ? 'yellow' : 'red')) }}-100 px-3 py-1 text-sm font-semibold leading-5 text-{{ $issue->statut === 'resolved' ? 'green' : ($issue->statut === 'in_progress' ? 'blue' : ($issue->statut === 'open' ? 'yellow' : 'red')) }}-800">
                {{ ucfirst($issue->statut) }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informations de la réclamation</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Titre:</span> {{ $issue->titre }}</p>
                    <p><span class="font-medium">Type:</span> {{ ucfirst(str_replace('_', ' ', $issue->type)) }}</p>
                    <p><span class="font-medium">Fournisseur:</span> {{ $issue->fournisseur?->raison_sociale ?? 'N/A' }}</p>
                    <p><span class="font-medium">Créé par:</span> {{ $issue->createdBy?->name ?? 'N/A' }}</p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Dates et statut</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Statut:</span> {{ ucfirst($issue->statut) }}</p>
                    <p><span class="font-medium">Date de création:</span> {{ $issue->created_at->format('d/m/Y H:i') }}</p>
                    @if($issue->resolved_at)
                        <p><span class="font-medium">Date de résolution:</span> {{ $issue->resolved_at->format('d/m/Y H:i') }}</p>
                    @endif
                    @if($issue->resolved_by)
                        <p><span class="font-medium">Résolu par:</span> {{ $issue->resolvedBy?->name ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Description</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-700">{{ $issue->description }}</p>
            </div>
        </div>
        
        @if($issue->resolution_notes)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Notes de résolution</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700">{{ $issue->resolution_notes }}</p>
                </div>
            </div>
        @endif
        
        @if($issue->fournisseur && $issue->fournisseur->supplierOrders->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Commandes associées</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-primary-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Montant TTC</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($issue->fournisseur->supplierOrders->take(5) as $order)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <a href="{{ route('supplier.portal.orders.show', $order) }}" class="text-primary-600 hover:text-primary-800">{{ $order->code }}</a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $order->date_commande->format('d/m/Y') }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span class="inline-flex rounded-full bg-{{ $order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : ($order->statut === 'approved' ? 'blue' : 'red')) }}-100 px-2 text-xs font-semibold leading-5 text-{{ $order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : ($order->statut === 'approved' ? 'blue' : 'red')) }}-800">
                                            {{ ucfirst($order->statut) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ number_format($order->montant_ttc, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Aucune commande associée trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        
        <div class="mt-6 flex justify-end">
            <a href="{{ route('supplier.portal.issues') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection