@extends('layouts.app')

@section('title', 'Détails de l\'utilisateur')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Détails de l'utilisateur</h2>
            <div>
                <a href="{{ route('user-management.edit', $user) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('user-management.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
        
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations personnelles</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nom</label>
                            <p class="text-gray-900">{{ $user->prenom }} {{ $user->nom }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                            <p class="text-gray-900">{{ $user->telephone ?? 'Non renseigné' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Statut</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $user->statut === 'actif' ? 'bg-green-100 text-green-800' : 
                                   ($user->statut === 'inactif' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($user->statut) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date de création</label>
                            <p class="text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dernière connexion</label>
                            <p class="text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais connecté' }}</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rôles et permissions</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Rôles attribués</label>
                        <div class="flex flex-wrap gap-2">
                            @forelse($user->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    {{ $role->nom }}
                                </span>
                            @empty
                                <p class="text-gray-500 text-sm">Aucun rôle attribué</p>
                            @endforelse
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Permissions</label>
                        <div class="flex flex-wrap gap-2">
                            @forelse($user->permissions as $permission)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $permission->nom }}
                                </span>
                            @empty
                                <p class="text-gray-500 text-sm">Aucune permission spécifique</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection