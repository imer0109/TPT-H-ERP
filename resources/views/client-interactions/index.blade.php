@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Interactions Clients</h1>
        <div>
            <a href="{{ route('client-interactions.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i> Nouvelle Interaction
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('client-interactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Rechercher..." 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
            </div>
            
            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                <select name="client_id" id="client_id" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->nom_raison_sociale }} ({{ $client->code_client }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="type_interaction" class="block text-sm font-medium text-gray-700 mb-1">Type d'interaction</label>
                <select name="type_interaction" id="type_interaction" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les types</option>
                    <option value="appel" {{ request('type_interaction') == 'appel' ? 'selected' : '' }}>Appel</option>
                    <option value="email" {{ request('type_interaction') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="reunion" {{ request('type_interaction') == 'reunion' ? 'selected' : '' }}>Réunion</option>
                    <option value="visite" {{ request('type_interaction') == 'visite' ? 'selected' : '' }}>Visite</option>
                    <option value="autre" {{ request('type_interaction') == 'autre' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>
            
            <div>
                <label for="suivi_necessaire" class="block text-sm font-medium text-gray-700 mb-1">Suivi nécessaire</label>
                <select name="suivi_necessaire" id="suivi_necessaire" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous</option>
                    <option value="1" {{ request('suivi_necessaire') == '1' ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ request('suivi_necessaire') == '0' ? 'selected' : '' }}>Non</option>
                </select>
            </div>
            
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="{{ route('client-interactions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded ml-2">
                    <i class="fas fa-undo mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des interactions -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                        Client
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                        Date
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                        Résultat
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                        Suivi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                        Utilisateur
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-primary-700 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($interactions as $interaction)
                <tr class="hover:bg-primary-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $interaction->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <a href="{{ route('clients.show', $interaction->client->id) }}" class="text-red-600 hover:text-red-800">
                            {{ $interaction->client->nom_raison_sociale }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($interaction->type_interaction == 'appel')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                <i class="fas fa-phone-alt mr-1"></i> Appel
                            </span>
                        @elseif($interaction->type_interaction == 'email')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-envelope mr-1"></i> Email
                            </span>
                        @elseif($interaction->type_interaction == 'reunion')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-users mr-1"></i> Réunion
                            </span>
                        @elseif($interaction->type_interaction == 'visite')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-building mr-1"></i> Visite
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-comment-dots mr-1"></i> Autre
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $interaction->date_interaction->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                        {{ $interaction->resultat ?: '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($interaction->suivi_necessaire)
                            @if($interaction->suivi_complete)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Complété
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-clock mr-1"></i> En attente
                                </span>
                                @if($interaction->date_suivi)
                                    <div class="text-xs text-gray-500 mt-1">{{ $interaction->date_suivi->format('d/m/Y') }}</div>
                                @endif
                            @endif
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-times-circle mr-1"></i> Non requis
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $interaction->user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('client-interactions.show', $interaction->id) }}" class="text-primary-600 hover:text-primary-900" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('client-interactions.edit', $interaction->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('client-interactions.destroy', $interaction->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette interaction ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                        Aucune interaction trouvée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $interactions->links() }}
    </div>
@endsection
