@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Liste des agences</h1>
        <a href="{{ route('agencies.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Nouvelle agence</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Advanced Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="{{ route('agencies.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" 
                    placeholder="Nom, code, zone, adresse...">
                <p class="text-xs text-gray-500 mt-1">Séparez les mots-clés par des espaces ou des virgules pour une recherche avancée</p>
            </div>
            
            <div>
                <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">Société</label>
                <select name="company_id" id="company_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Toutes les sociétés</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->raison_sociale }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="en veille" {{ request('status') === 'en veille' ? 'selected' : '' }}>En veille</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded-lg mr-2">
                    Filtrer
                </button>
                <a href="{{ route('agencies.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Société</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Responsable</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Zone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($agencies as $agency)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $agency->code_unique }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agency->nom }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agency->company->raison_sociale }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agency->responsable?->name?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agency->zone_geographique }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $agency->statut == 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $agency->statut == 'active' ? 'Active' : 'En veille' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('companies.dashboard.agency', $agency->id) }}"
                           class="text-primary-600 hover:text-primary-900 inline-flex items-center mr-3">
                            📊 Dashboard
                        </a>
                        <a href="{{ route('agencies.edit', $agency->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">✏ Modifier</a>
                        <form action="{{ route('agencies.duplicate', $agency) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3"
                                    onclick="return confirm('Êtes-vous sûr de vouloir dupliquer cette agence ?')">
                                📋 Dupliquer
                            </button>
                        </form>
                        @if($agency->statut == 'active')
                            <form action="{{ route('agencies.archive', $agency) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 mr-3"
                                        onclick="return confirm('Êtes-vous sûr de vouloir archiver cette agence ?')">
                                    📦 Archiver
                                </button>
                            </form>
                        @else
                            <form action="{{ route('agencies.archive', $agency) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-primary-600 hover:text-primary-900 mr-3"
                                        onclick="return confirm('Êtes-vous sûr de vouloir réactiver cette agence ?')">
                                    🔁 Réactiver
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('agencies.destroy', $agency->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette agence ?')">🗑 Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucune agence trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 bg-gray-50">
            {{ $agencies->links() }}
        </div>
    </div>
</div>
@endsection