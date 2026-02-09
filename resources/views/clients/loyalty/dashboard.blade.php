@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Fidélité</h1>
        <div class="flex space-x-2">
            <a href="{{ route('clients.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Dashboard Clients
            </a>
            <a href="{{ route('clients.loyalty.index') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-list mr-2"></i> Cartes de Fidélité
            </a>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-primary-100 text-primary-600 mr-4">
                    <i class="fas fa-id-card fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Cartes</p>
                    <p class="text-2xl font-bold">{{ $totalCards }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-coins fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Points Totals</p>
                    <p class="text-2xl font-bold">{{ $totalPoints }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Cartes Actives</p>
                    <p class="text-2xl font-bold">{{ $activeCards }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-crown fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Membres Platine</p>
                    <p class="text-2xl font-bold">{{ $platinumMembers }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tier Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Répartition par Niveau</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Niveau</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Nombre de Cartes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Points Totals</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $totalTierCards = $tierDistribution->sum('card_count');
                        @endphp
                        @forelse($tierDistribution as $tier)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $tier->tier == 'bronze' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $tier->tier == 'silver' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $tier->tier == 'gold' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $tier->tier == 'platinum' ? 'bg-primary-100 text-primary-800' : '' }}
                                ">
                                    {{ ucfirst($tier->tier) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $tier->card_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($tier->total_points, 0, ',', ' ') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $totalTierCards > 0 ? number_format(($tier->card_count / $totalTierCards) * 100, 1) : 0 }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Aucune donnée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Transactions Récentes</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Points</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->loyaltyCard->client->nom_raison_sociale }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transaction->type == 'earned' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type == 'earned' ? '+' : '-' }}{{ $transaction->points }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $transaction->type == 'earned' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}
                                ">
                                    {{ $transaction->type == 'earned' ? 'Gagnés' : 'Utilisés' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Aucune transaction</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Top Clients by Points -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Top Clients par Points</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Carte</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Points</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Niveau</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Dernière Transaction</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topClients as $card)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $card->client->nom_raison_sociale }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $card->card_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $card->points }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $card->tier == 'bronze' ? 'bg-amber-100 text-amber-800' : '' }}
                                {{ $card->tier == 'silver' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $card->tier == 'gold' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $card->tier == 'platinum' ? 'bg-primary-100 text-primary-800' : '' }}
                            ">
                                {{ ucfirst($card->tier) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $card->last_transaction_at ? $card->last_transaction_at->format('d/m/Y H:i') : 'Jamais' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Aucun client</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection