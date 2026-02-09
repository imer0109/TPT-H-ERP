@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Profils Utilisateurs</h1>
        <div class="flex space-x-2">
            <a href="{{ route('user-profiles.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Nouvel Utilisateur
            </a>
            <a href="#" onclick="document.getElementById('exportForm').submit();" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-file-export mr-2"></i> Exporter
            </a>
        </div>
    </div>

    <form id="exportForm" action="{{ route('user-profiles.export') }}" method="POST" class="hidden">@csrf</form>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('user-profiles.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, prénom, email..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 px-3 py-2">
            <select name="company_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 px-3 py-2">
                <option value="">Toutes les entreprises</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->raison_sociale }}
                    </option>
                @endforeach
            </select>
            <select name="statut" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 px-3 py-2">
                <option value="">Tous les statuts</option>
                <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
            </select>
            <select name="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 px-3 py-2">
                <option value="">Tous les rôles</option>
                <option value="administrateur" {{ request('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                <option value="utilisateur" {{ request('role') == 'utilisateur' ? 'selected' : '' }}>Utilisateur</option>
                <option value="gestionnaire" {{ request('role') == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
            </select>

            <div class="md:col-span-4 flex justify-end space-x-2 mt-2">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="{{ route('user-profiles.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-undo mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau responsive -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Nom</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Rôles</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Entreprise</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-primary-50 transition duration-150">
                    <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ $user->id }}</td>
                    <td class="px-4 py-2 text-sm text-primary-600 hover:text-primary-800">
                        <a href="{{ route('user-profiles.show', $user->id) }}">{{ $user->prenom }} {{ $user->nom }}</a>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $user->email }}</td>
                    <td class="px-4 py-2 text-sm">
                        @foreach($user->roles as $role)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800 mr-1">
                                {{ $role->nom }}
                            </span>
                        @endforeach
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $user->company->raison_sociale ?? '-' }}</td>
                    <td class="px-4 py-2 text-sm">
                        @if($user->statut == 'actif')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Actif
                            </span>
                        @elseif($user->statut == 'inactif')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-user-clock mr-1"></i> Inactif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-user-slash mr-1"></i> Suspendu
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-sm text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('user-profiles.show', $user->id) }}" class="text-primary-600 hover:text-primary-900" title="Voir"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('user-profiles.edit', $user->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Modifier"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('user-profiles.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">Aucun utilisateur trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection