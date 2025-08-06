@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Détails de l'Interaction Client</h1>
        <div class="flex space-x-2">
            <a href="{{ route('client-interactions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
            <a href="{{ route('client-interactions.edit', $interaction->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Informations principales -->
            <div class="md:col-span-2">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informations de l'interaction</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Client</p>
                            <p class="text-base font-medium">
                                <a href="{{ route('clients.show', $interaction->client->id) }}" class="text-red-600 hover:text-red-800">
                                    {{ $interaction->client->nom_raison_sociale }} ({{ $interaction->client->code_client }})
                                </a>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Type d'interaction</p>
                            <p class="text-base">
                                @if($interaction->type_interaction == 'appel')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date de l'interaction</p>
                            <p class="text-base">{{ $interaction->date_interaction->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Utilisateur</p>
                            <p class="text-base">{{ $interaction->user->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Suivi nécessaire</p>
                            <p class="text-base">
                                @if($interaction->suivi_necessaire)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-check-circle mr-1"></i> Oui
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-times-circle mr-1"></i> Non
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        @if($interaction->suivi_necessaire && $interaction->date_suivi)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date de suivi</p>
                            <p class="text-base">{{ $interaction->date_suivi->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        
                        @if($interaction->campagne)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Campagne</p>
                            <p class="text-base">{{ $interaction->campagne->nom }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Description</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="whitespace-pre-line">{{ $interaction->description }}</p>
                    </div>
                </div>
                
                @if($interaction->resultat)
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Résultat</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="whitespace-pre-line">{{ $interaction->resultat }}</p>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Actions et documents -->
            <div class="md:col-span-1">
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-md font-semibold text-gray-700 mb-4">Actions</h3>
                    
                    <div class="space-y-2">
                        @if($interaction->suivi_necessaire && !$interaction->suivi_complete)
                        <form action="{{ route('client-interactions.mark-followed-up', $interaction->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                                <i class="fas fa-check mr-2"></i> Marquer comme suivi
                            </button>
                        </form>
                        @endif
                        
                        <form action="{{ route('client-interactions.destroy', $interaction->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette interaction ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">
                                <i class="fas fa-trash-alt mr-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-md font-semibold text-gray-700 mb-4">Documents</h3>
                    
                    @if($interaction->documents->count() > 0)
                    <div class="space-y-3 mb-4">
                        @foreach($interaction->documents as $document)
                        <div class="flex items-center justify-between bg-white p-3 rounded-md shadow-sm">
                            <div class="flex items-center">
                                @if(in_array(pathinfo($document->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                    <i class="fas fa-file-image text-blue-500 mr-2"></i>
                                @elseif(pathinfo($document->file_path, PATHINFO_EXTENSION) == 'pdf')
                                    <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                @else
                                    <i class="fas fa-file text-gray-500 mr-2"></i>
                                @endif
                                <span class="text-sm truncate" title="{{ $document->original_name }}">{{ $document->original_name }}</span>
                            </div>
                            <div class="flex space-x-1">
                                <a href="{{ route('documents.view', $document->id) }}" target="_blank" class="text-blue-500 hover:text-blue-700" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('documents.download', $document->id) }}" class="text-green-500 hover:text-green-700" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form action="{{ route('documents.destroy', $document->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-500 mb-4">Aucun document associé à cette interaction.</p>
                    @endif
                    
                    <form action="{{ route('client-interactions.upload-document', $interaction->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="document" class="block text-sm font-medium text-gray-700 mb-1">Ajouter un document</label>
                            <input type="file" name="document" id="document" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        </div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-upload mr-2"></i> Téléverser
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection