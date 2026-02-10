@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Utilisateurs</h1>
        <x-button href="{{ route('user-management.create') }}" variant="primary">
            <i class="fas fa-plus mr-2 text-white "></i>Nouvel Utilisateur
        </x-button>
    </div>

    <x-table>
        <x-slot name="header">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Rôles</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
            </tr>
        </x-slot>
        
        <x-slot name="body">
            @foreach($users as $user)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $user->prenom }} {{ $user->nom }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">
                        @foreach($user->roles as $role)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 mr-1">
                                {{ $role->nom }}
                            </span>
                        @endforeach
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $user->statut === 'actif' ? 'bg-green-100 text-green-800' : 
                           ($user->statut === 'inactif' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($user->statut) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <a href="{{ route('user-management.show', $user) }}" class="text-primary-600 hover:text-primary-900 mr-2">
                        <i class="fas fa-eye"></i> Voir
                    </a>
                    <a href="{{ route('user-management.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <form action="{{ route('user-management.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </x-slot>
        
        <x-slot name="pagination">
            {{ $users->links() }}
        </x-slot>
    </x-table>
</div>
@endsection