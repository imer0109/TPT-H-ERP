@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Modifier la Carte de Fidélité</h1>
        <a href="{{ route('clients.show', $loyaltyCard->client) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Retour au Client
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('clients.loyalty.update', $loyaltyCard) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="client_info" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <div class="font-medium">{{ $loyaltyCard->client->nom_raison_sociale }}</div>
                        <div class="text-sm text-gray-500">{{ $loyaltyCard->client->code_client }}</div>
                    </div>
                </div>
                
                <div>
                    <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro de Carte</label>
                    <div class="p-3 bg-gray-50 rounded-md font-mono">
                        {{ $loyaltyCard->card_number }}
                    </div>
                </div>
                
                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700 mb-1">Points *</label>
                    <input type="number" name="points" id="points" min="0" value="{{ old('points', $loyaltyCard->points) }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    @error('points')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tier" class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $loyaltyCard->tier == 'bronze' ? 'bg-amber-100 text-amber-800' : '' }}
                            {{ $loyaltyCard->tier == 'silver' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $loyaltyCard->tier == 'gold' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $loyaltyCard->tier == 'platinum' ? 'bg-primary-100 text-primary-800' : '' }}
                        ">
                            {{ ucfirst($loyaltyCard->tier) }}
                        </span>
                    </div>
                </div>
                
                <div>
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Date d'Expiration</label>
                    <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at', $loyaltyCard->expires_at ? $loyaltyCard->expires_at->format('Y-m-d') : '') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    @error('expires_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut *</label>
                    <select name="status" id="status" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        <option value="active" {{ old('status', $loyaltyCard->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $loyaltyCard->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status', $loyaltyCard->status) == 'suspended' ? 'selected' : '' }}>Suspendue</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="last_transaction_at" class="block text-sm font-medium text-gray-700 mb-1">Dernière Transaction</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        {{ $loyaltyCard->last_transaction_at ? $loyaltyCard->last_transaction_at->format('d/m/Y H:i') : 'Jamais' }}
                    </div>
                </div>
                
                <div>
                    <label for="issued_at" class="block text-sm font-medium text-gray-700 mb-1">Date d'Émission</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        {{ $loyaltyCard->issued_at ? $loyaltyCard->issued_at->format('d/m/Y') : 'N/A' }}
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <a href="{{ route('clients.show', $loyaltyCard->client) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Annuler
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
    
    <!-- Points Management -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Add Points -->
        <div class="bg-green-50 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Ajouter des Points</h2>
            <form action="{{ route('clients.loyalty.add-points', $loyaltyCard) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="add_points" class="block text-sm font-medium text-gray-700 mb-1">Nombre de Points</label>
                    <input type="number" name="points" id="add_points" min="1" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                </div>
                <div class="mb-4">
                    <label for="add_description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optionnel)</label>
                    <input type="text" name="description" id="add_description" maxlength="255"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i> Ajouter des Points
                </button>
            </form>
        </div>
        
        <!-- Redeem Points -->
        <div class="bg-red-50 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Utiliser des Points</h2>
            <form action="{{ route('clients.loyalty.redeem-points', $loyaltyCard) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="redeem_points" class="block text-sm font-medium text-gray-700 mb-1">Nombre de Points</label>
                    <input type="number" name="points" id="redeem_points" min="1" max="{{ $loyaltyCard->points }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <p class="mt-1 text-sm text-gray-500">Points disponibles : {{ $loyaltyCard->points }}</p>
                </div>
                <div class="mb-4">
                    <label for="redeem_description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optionnel)</label>
                    <input type="text" name="description" id="redeem_description" maxlength="255"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                </div>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-minus mr-2"></i> Utiliser des Points
                </button>
            </form>
        </div>
    </div>
    
    <!-- Transaction History -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Historique des Transactions</h2>
        @if($loyaltyCard->transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Points</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Description</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($loyaltyCard->transactions->sortByDesc('created_at')->take(10) as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
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
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->description ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($loyaltyCard->transactions->count() > 10)
                <div class="mt-4 text-center">
                    <a href="#" class="text-red-600 hover:text-red-800">
                        Voir tout l'historique ({{ $loyaltyCard->transactions->count() }} transactions)
                    </a>
                </div>
            @endif
        @else
            <p class="text-gray-500 italic">Aucune transaction enregistrée</p>
        @endif
    </div>
</div>
@endsection