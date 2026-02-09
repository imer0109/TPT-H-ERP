@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Contrats Fournisseurs</h1>
        <div class="flex space-x-2">
            <a href="{{ route('fournisseurs.contracts.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i> Nouveau Contrat
            </a>
            <a href="{{ route('fournisseurs.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-chart-line mr-2"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('fournisseurs.contracts.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
            </div>

            <div>
                <label for="fournisseur_id" class="block text-sm font-medium text-gray-700 mb-1">Fournisseur</label>
                <select name="fournisseur_id" id="fournisseur_id" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les fournisseurs</option>
                    @foreach($fournisseurs as $fournisseur)
                        <option value="{{ $fournisseur->id }}" {{ request('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                            {{ $fournisseur->raison_sociale }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" id="status" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expiré</option>
                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Résilié</option>
                </select>
            </div>

            <div class="flex items-end">
                <div class="flex items-center">
                    <input type="checkbox" name="expiring_soon" id="expiring_soon" value="1" 
                        class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                        {{ request('expiring_soon') ? 'checked' : '' }}>
                    <label for="expiring_soon" class="ml-2 block text-sm text-gray-700">
                        Expirant bientôt
                    </label>
                </div>
                <div class="ml-2">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-filter mr-2"></i> Filtrer
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Contracts List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Contrat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Fournisseur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Valeur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($contracts as $contract)
                <tr class="hover:bg-primary-50">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $contract->contract_number }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($contract->description, 30) }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <a href="{{ route('fournisseurs.show', $contract->fournisseur) }}" class="text-primary-600 hover:text-primary-900">
                            {{ $contract->fournisseur->raison_sociale }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $contract->contract_type }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <div>Du {{ $contract->start_date->format('d/m/Y') }}</div>
                        <div>Au {{ $contract->end_date->format('d/m/Y') }}</div>
                        @if($contract->isExpiringSoon())
                            <div class="text-yellow-600 font-medium">
                                Expire dans {{ $contract->days_until_expiry }} jours
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        @if($contract->value)
                            {{ number_format($contract->value, 2, ',', ' ') }} {{ $contract->currency }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        {!! $contract->status_badge !!}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('fournisseurs.contracts.show', $contract) }}" class="text-primary-600 hover:text-primary-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('fournisseurs.contracts.edit', $contract) }}" class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('fournisseurs.contracts.destroy', $contract) }}" method="POST" 
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                        Aucun contrat trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4">
            {{ $contracts->links() }}
        </div>
    </div>
</div>
@endsection