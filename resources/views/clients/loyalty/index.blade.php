@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Cartes de Fidélité</h1>
        <a href="{{ route('clients.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Retour au Dashboard
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('clients.loyalty.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                       placeholder="Numéro de carte ou nom du client">
            </div>
            
            <div>
                <label for="tier" class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
                <select name="tier" id="tier" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <option value="">Tous</option>
                    <option value="bronze" {{ request('tier') == 'bronze' ? 'selected' : '' }}>Bronze</option>
                    <option value="silver" {{ request('tier') == 'silver' ? 'selected' : '' }}>Argent</option>
                    <option value="gold" {{ request('tier') == 'gold' ? 'selected' : '' }}>Or</option>
                    <option value="platinum" {{ request('tier') == 'platinum' ? 'selected' : '' }}>Platine</option>
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" id="status" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <option value="">Tous</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendue</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
                <a href="{{ route('clients.loyalty.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    <i class="fas fa-times mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Loyalty Cards Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Numéro de Carte</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Points</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Niveau</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Dernière Transaction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loyaltyCards as $card)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $card->client->nom_raison_sociale }}</div>
                            <div class="text-sm text-gray-500">{{ $card->client->code_client }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $card->card_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $card->points }}
                        </td>
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
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $card->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $card->status == 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $card->status == 'suspended' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                                {{ ucfirst($card->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $card->last_transaction_at ? $card->last_transaction_at->format('d/m/Y H:i') : 'Jamais' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('clients.loyalty.edit', $card) }}" class="text-red-600 hover:text-red-900 mr-3">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="{{ route('clients.loyalty.destroy', $card) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte de fidélité?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            Aucune carte de fidélité trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $loyaltyCards->links() }}
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-primary-100 text-primary-600 mr-4">
                    <i class="fas fa-id-card fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Cartes</p>
                    <p class="text-2xl font-bold">{{ $loyaltyCards->total() }}</p>
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
                    <p class="text-2xl font-bold">{{ $loyaltyCards->sum('points') }}</p>
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
                    <p class="text-2xl font-bold">{{ $loyaltyCards->where('status', 'active')->count() }}</p>
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
                    <p class="text-2xl font-bold">{{ $loyaltyCards->where('tier', 'platinum')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection