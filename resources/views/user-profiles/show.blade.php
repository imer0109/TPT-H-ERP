@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Profil de {{ $user->prenom }} {{ $user->nom }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('user-profiles.edit', $user->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
                <a href="{{ route('user-profiles.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations utilisateur -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Informations personnelles</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Nom</p>
                                <p class="text-lg font-medium text-gray-900">{{ $user->nom }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Prénom</p>
                                <p class="text-lg font-medium text-gray-900">{{ $user->prenom }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="text-lg font-medium text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Téléphone</p>
                                <p class="text-lg font-medium text-gray-900">{{ $user->telephone ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Informations professionnelles</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Entreprise</p>
                                <p class="text-lg font-medium text-gray-900">{{ $user->company->raison_sociale ?? 'Non assigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Statut</p>
                                <p class="text-lg font-medium">
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
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Rôles et Permissions</h2>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 mb-2">Rôles attribués :</p>
                            <div class="flex flex-wrap gap-2">
                                @forelse($user->roles as $role)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                                        {{ $role->nom }}
                                    </span>
                                @empty
                                    <p class="text-gray-500">Aucun rôle attribué</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div>
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            @if($user->photo)
                                <img src="{{ Storage::url($user->photo) }}" alt="Photo de profil" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                            @else
                                <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center border-4 border-gray-200">
                                    <span class="text-4xl text-gray-500">{{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->prenom }} {{ $user->nom }}</h3>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-500">Créé le</p>
                            <p class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Dernière mise à jour</p>
                            <p class="text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Documents</h2>
                    </div>
                    <div class="p-4">
                        @forelse($user->documents as $document)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                <div class="flex items-center">
                                    <i class="fas fa-file text-primary-500 mr-2"></i>
                                    <span class="text-sm text-gray-700 truncate max-w-xs">{{ $document->nom_fichier }}</span>
                                </div>
                                <a href="#" class="text-primary-600 hover:text-primary-900 text-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm py-2">Aucun document</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
                    <div class="p-4">
                        <form action="{{ route('user-profiles.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded flex items-center justify-center">
                                <i class="fas fa-trash mr-2"></i> Supprimer l'utilisateur
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection