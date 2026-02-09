@extends('fournisseurs.portal.layout')

@section('title', 'Tableau de bord Fournisseur')

@section('header', 'Tableau de bord')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Welcome message -->
    <div class="mb-6 rounded-lg bg-white p-6 shadow">
        <h1 class="text-2xl font-bold text-gray-800">Bienvenue, {{ $supplier ? $supplier->raison_sociale : (auth()->user()->nom ?? 'Utilisateur') }}</h1>
        <p class="mt-2 text-gray-600">Voici un aperçu de vos activités récentes avec notre entreprise.</p>
    </div>
    
    <!-- Key metrics -->
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="flex items-center">
                <div class="rounded-full bg-primary-100 p-3 text-primary-600">
                    <i class="fas fa-file-invoice fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Commandes totales</p>
                    <p class="text-2xl font-bold">{{ $supplier ? $supplier->supplierOrders()->count() : 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 text-green-600">
                    <i class="fas fa-truck-loading fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Livraisons</p>
                    <p class="text-2xl font-bold">{{ $supplier ? $supplier->supplierDeliveries()->count() : 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="flex items-center">
                <div class="rounded-full bg-yellow-100 p-3 text-yellow-600">
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Solde</p>
                    <p class="text-2xl font-bold">{{ number_format($outstandingBalance, 0, ',', ' ') }} FCFA</p>
                </div>
            </div>
        </div>
        
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="flex items-center">
                <div class="rounded-full bg-red-100 p-3 text-red-600">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Réclamations ouvertes</p>
                    <p class="text-2xl font-bold">{{ $openIssues->count() }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent activities -->
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent orders -->
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Commandes récentes</h2>
                <a href="{{ route('supplier.portal.orders') }}" class="text-sm text-primary-600 hover:text-primary-800">
                    Voir tout
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Montant</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($recentOrders as $order)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-900">{{ $order->code }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500">{{ $order->date_commande->format('d/m/Y') }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500">{{ number_format($order->montant_ttc, 0, ',', ' ') }} FCFA</td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm">
                                    <span class="inline-flex rounded-full bg-{{ $order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : 'gray') }}-100 px-2 text-xs font-semibold leading-5 text-{{ $order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : 'gray') }}-800">
                                        {{ ucfirst($order->statut) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">Aucune commande trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Recent deliveries -->
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Livraisons récentes</h2>
                <a href="{{ route('supplier.portal.deliveries') }}" class="text-sm text-primary-600 hover:text-primary-800">
                    Voir tout
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">BL</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Commande</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($recentDeliveries as $delivery)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-900">{{ $delivery->numero_bl }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500">{{ $delivery->date_reception->format('d/m/Y') }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500">{{ $delivery->order?->code ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm">
                                    <span class="inline-flex rounded-full bg-{{ $delivery->statut === 'completed' ? 'green' : ($delivery->statut === 'partial' ? 'yellow' : 'gray') }}-100 px-2 text-xs font-semibold leading-5 text-{{ $delivery->statut === 'completed' ? 'green' : ($delivery->statut === 'partial' ? 'yellow' : 'gray') }}-800">
                                        {{ ucfirst($delivery->statut) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">Aucune livraison trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Contracts and issues -->
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Active contracts -->
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Contrats actifs</h2>
                <a href="{{ route('supplier.portal.contracts') }}" class="text-sm text-primary-600 hover:text-primary-800">
                    Voir tout
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Contrat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Fin</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Jours restants</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($activeContracts as $contract)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-900">{{ $contract->contract_number }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500">{{ $contract->end_date->format('d/m/Y') }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500">
                                    <span class="{{ $contract->days_until_expiry <= 30 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                        {{ $contract->days_until_expiry }} jours
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500">Aucun contrat actif</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Open issues -->
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Réclamations ouvertes</h2>
                <a href="{{ route('supplier.portal.issues') }}" class="text-sm text-primary-600 hover:text-primary-800">
                    Voir tout
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Titre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($openIssues as $issue)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-900">{{ $issue->titre }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $issue->type)) }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500">{{ $issue->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500">Aucune réclamation ouverte</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection