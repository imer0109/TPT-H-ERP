@extends('layouts.app')

@section('title', 'Documents du fournisseur - ' . $fournisseur->raison_sociale)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Documents du fournisseur: {{ $fournisseur->raison_sociale }}
                    </h2>
                    <a href="{{ route('fournisseurs.documents.create', $fournisseur) }}" 
                       class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                        Ajouter un document
                    </a>
                </div>

                <!-- Supplier Info -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Code Fournisseur</p>
                            <p class="font-semibold">{{ $fournisseur->code_fournisseur }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Documents</p>
                            <p class="font-semibold">{{ $fournisseur->documents()->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Documents Expirant Bientôt</p>
                            <p class="font-semibold">{{ $fournisseur->documents()->expirantBientot()->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Documents Table -->
                @if($documents->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-primary-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                                    Nom
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                                    Date d'expiration
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                                    Taille
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($documents as $document)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $document->nom }}</div>
                                    @if($document->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($document->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $document->type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($document->date_expiration)
                                        {{ $document->date_expiration->format('d/m/Y') }}
                                        @if($document->isExpiringSoon())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                                Expiration proche
                                            </span>
                                        @endif
                                        @if($document->isExpired())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                                Expiré
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($document->taille / 1024, 2) }} KB
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($document->isExpired())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Expiré
                                        </span>
                                    @elseif($document->isExpiringSoon())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Expiration proche
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Valide
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('fournisseurs.documents.show', [$fournisseur, $document]) }}" 
                                       class="text-primary-600 hover:text-primary-900 mr-3">Voir</a>
                                    <a href="{{ route('fournisseurs.documents.edit', [$fournisseur, $document]) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                                    <a href="{{ route('fournisseurs.documents.download', [$fournisseur, $document]) }}" 
                                       class="text-green-600 hover:text-green-900 mr-3">Télécharger</a>
                                    <form action="{{ route('fournisseurs.documents.destroy', [$fournisseur, $document]) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document?')">
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $documents->links() }}
                </div>
                @else
                <div class="text-center py-12">
                    <p class="text-gray-500">Aucun document trouvé pour ce fournisseur.</p>
                    <a href="{{ route('fournisseurs.documents.create', $fournisseur) }}" 
                       class="mt-4 inline-block bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                        Ajouter un document
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection