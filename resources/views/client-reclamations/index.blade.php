@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Réclamations Clients</h1>
        <div>
            <a href="{{ route('client-reclamations.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i> Nouvelle réclamation
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('client-reclamations.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="w-full md:w-auto flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                    placeholder="Rechercher par client, description..." 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
            </div>
            
            <div class="w-full md:w-auto flex-1 min-w-[200px]">
                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                <select name="client_id" id="client_id" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->nom_raison_sociale }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-auto flex-1 min-w-[200px]">
                <label for="type_reclamation" class="block text-sm font-medium text-gray-700 mb-1">Type de réclamation</label>
                <select name="type_reclamation" id="type_reclamation" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les types</option>
                    <option value="qualite_produit" {{ request('type_reclamation') == 'qualite_produit' ? 'selected' : '' }}>Qualité produit</option>
                    <option value="service_client" {{ request('type_reclamation') == 'service_client' ? 'selected' : '' }}>Service client</option>
                    <option value="livraison" {{ request('type_reclamation') == 'livraison' ? 'selected' : '' }}>Livraison</option>
                    <option value="facturation" {{ request('type_reclamation') == 'facturation' ? 'selected' : '' }}>Facturation</option>
                    <option value="autre" {{ request('type_reclamation') == 'autre' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>
            
            <div class="w-full md:w-auto flex-1 min-w-[200px]">
                <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" id="statut" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les statuts</option>
                    <option value="ouverte" {{ request('statut') == 'ouverte' ? 'selected' : '' }}>Ouverte</option>
                    <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="resolue" {{ request('statut') == 'resolue' ? 'selected' : '' }}>Résolue</option>
                    <option value="fermee" {{ request('statut') == 'fermee' ? 'selected' : '' }}>Fermée</option>
                </select>
            </div>
            
            <div class="w-full md:w-auto">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="{{ route('client-reclamations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded ml-2">
                    <i class="fas fa-undo mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des réclamations -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Client</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Agent</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Date création</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reclamations as $reclamation)
                    <tr class="hover:bg-primary-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $reclamation->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="{{ route('clients.show', $reclamation->client) }}" class="text-primary-600 hover:text-primary-900">
                                {{ $reclamation->client->nom_raison_sociale }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @switch($reclamation->type_reclamation)
                                @case('qualite_produit')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Qualité produit</span>
                                    @break
                                @case('service_client')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">Service client</span>
                                    @break
                                @case('livraison')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Livraison</span>
                                    @break
                                @case('facturation')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Facturation</span>
                                    @break
                                @default
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Autre</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                            {{ $reclamation->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @switch($reclamation->statut)
                                @case('ouverte')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ouverte</span>
                                    @break
                                @case('en_cours')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">En cours</span>
                                    @break
                                @case('resolue')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Résolue</span>
                                    @break
                                @case('fermee')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Fermée</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $reclamation->agent ? $reclamation->agent->nom . ' ' . $reclamation->agent->prenom : 'Non assigné' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $reclamation->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('client-reclamations.show', $reclamation) }}" class="text-primary-600 hover:text-primary-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('client-reclamations.edit', $reclamation) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('client-reclamations.destroy', $reclamation) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Aucune réclamation trouvée
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $reclamations->links() }}
    </div>
</div>
@endsection
