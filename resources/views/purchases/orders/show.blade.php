@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Bon de Commande {{ $order->code }}</h1>
                <div class="flex items-center space-x-4 mt-2">
                    @php
                        $statusColors = [
                            'Brouillon' => 'bg-gray-100 text-gray-800',
                            'En attente' => 'bg-yellow-100 text-yellow-800',
                            'Envoyé' => 'bg-primary-100 text-primary-800',
                            'Confirmé' => 'bg-green-100 text-green-800',
                            'Livré' => 'bg-purple-100 text-purple-800',
                            'Clôturé' => 'bg-gray-100 text-gray-800',
                            'Annulé' => 'bg-red-100 text-red-800'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$order->statut] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $order->statut }}
                    </span>
                    <span class="text-sm text-gray-600">Créé le {{ $order->created_at->format('d/m/Y à H:i') }}</span>
                    @if($order->purchaseRequest)
                        <span class="text-sm text-primary-600">
                            <i class="fas fa-link mr-1"></i>DA: {{ $order->purchaseRequest->code }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('purchases.orders.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
                @if(in_array($order->statut, ['Brouillon', 'En attente']))
                    <a href="{{ route('purchases.orders.edit', $order) }}" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                @endif
                <a href="{{ route('purchases.orders.pdf', $order) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-file-pdf mr-2"></i>PDF
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails commande -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Détails de la commande</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nature d'achat</p>
                        <p class="text-base">{{ $order->nature_achat }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de commande</p>
                        <p class="text-base">{{ $order->date_commande->format('d/m/Y') }}</p>
                    </div>
                    @if($order->delai_contractuel)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Délai contractuel</p>
                        <p class="text-base">{{ $order->delai_contractuel->format('d/m/Y') }}</p>
                    </div>
                    @endif
                    @if($order->conditions_paiement)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Conditions de paiement</p>
                        <p class="text-base">{{ $order->conditions_paiement }}</p>
                    </div>
                    @endif
                    @if($order->adresse_livraison)
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500">Adresse de livraison</p>
                        <p class="text-base">{{ $order->adresse_livraison }}</p>
                    </div>
                    @endif
                    @if($order->notes)
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500">Notes</p>
                        <p class="text-base">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Articles -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Articles commandés</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-primary-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-primary-700 uppercase">Désignation</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-primary-700 uppercase">Qté</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-primary-700 uppercase">Unité</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-primary-700 uppercase">P.U.</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-primary-700 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->designation }}</div>
                                        @if($item->product)
                                            <div class="text-xs text-gray-500">Réf: {{ $item->product->reference ?? $item->product->name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-900">{{ $item->quantite }}</td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-900">{{ $item->unite }}</td>
                                    <td class="px-4 py-3 text-right text-sm text-gray-900">
                                        {{ number_format($item->prix_unitaire, 0, ',', ' ') }} {{ $order->devise }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                        {{ number_format($item->montant_total, 0, ',', ' ') }} {{ $order->devise }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-900">Total HT</td>
                                <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($order->montant_ht, 0, ',', ' ') }} {{ $order->devise }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-900">TVA</td>
                                <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($order->montant_tva, 0, ',', ' ') }} {{ $order->devise }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-lg font-bold text-gray-900">Total TTC</td>
                                <td class="px-4 py-3 text-right text-lg font-bold text-red-600">
                                    {{ number_format($order->montant_ttc, 0, ',', ' ') }} {{ $order->devise }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Fournisseur -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Fournisseur</h3>
                @if($order->fournisseur)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Raison sociale</p>
                            <p class="text-base font-medium">{{ $order->fournisseur->raison_sociale }}</p>
                        </div>
                        @if($order->fournisseur->contact_principal)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Contact</p>
                            <p class="text-base">{{ $order->fournisseur->contact_principal }}</p>
                        </div>
                        @endif
                        @if($order->fournisseur->telephone)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Téléphone</p>
                            <p class="text-base">
                                <a href="tel:{{ $order->fournisseur->telephone }}" class="text-primary-600 hover:text-primary-800">
                                    {{ $order->fournisseur->telephone }}
                                </a>
                            </p>
                        </div>
                        @endif
                        @if($order->fournisseur->email)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <p class="text-base">
                                <a href="mailto:{{ $order->fournisseur->email }}" class="text-primary-600 hover:text-primary-800">
                                    {{ $order->fournisseur->email }}
                                </a>
                            </p>
                        </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Agence -->
            @if($order->agency)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Agence</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nom</p>
                        <p class="text-base font-medium">{{ $order->agency->nom }}</p>
                    </div>
                    @if($order->agency->adresse)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Adresse</p>
                        <p class="text-base">{{ $order->agency->adresse }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Créé par -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Créé par</p>
                        <p class="text-base">{{ $order->createdBy->name ?? 'Utilisateur supprimé' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de création</p>
                        <p class="text-base">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    @if($order->updated_at != $order->created_at)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Dernière modification</p>
                        <p class="text-base">{{ $order->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                <div class="space-y-3">
                    @if($order->statut === 'Confirmé')
                        <a href="{{ route('purchases.orders.create-delivery', $order) }}" 
                           class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition text-center block">
                            <i class="fas fa-truck mr-2"></i>Créer une livraison
                        </a>
                        <a href="{{ route('purchases.orders.create-payment', $order) }}" 
                           class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition text-center block">
                            <i class="fas fa-credit-card mr-2"></i>Créer un paiement
                        </a>
                    @endif

                    @if(in_array($order->statut, ['Brouillon', 'En attente', 'Envoyé']))
                        <form method="POST" action="{{ route('purchases.orders.update-status', $order) }}" class="space-y-2">
                            @csrf
                            <select name="statut" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                @foreach(['Brouillon', 'En attente', 'Envoyé', 'Confirmé', 'Annulé'] as $statut)
                                    <option value="{{ $statut }}" {{ $order->statut == $statut ? 'selected' : '' }}>
                                        {{ $statut }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-save mr-2"></i>Changer le statut
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection