@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Détails de la Réclamation #{{ $reclamation->id }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('client-reclamations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
            <a href="{{ route('client-reclamations.edit', $reclamation) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informations de la réclamation</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Client</p>
                        <p class="text-base font-semibold">
                            <a href="{{ route('clients.show', $reclamation->client) }}" class="text-red-600 hover:text-red-800">
                                {{ $reclamation->client->nom_raison_sociale }} ({{ $reclamation->client->code_client }})
                            </a>
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Type de réclamation</p>
                        <p class="text-base">
                            @php
                                $typeClasses = [
                                    'qualite_produit' => 'bg-orange-100 text-orange-800',
                                    'service_client' => 'bg-blue-100 text-blue-800',
                                    'livraison' => 'bg-purple-100 text-purple-800',
                                    'facturation' => 'bg-yellow-100 text-yellow-800',
                                    'autre' => 'bg-gray-100 text-gray-800'
                                ];
                                $typeLabels = [
                                    'qualite_produit' => 'Qualité produit',
                                    'service_client' => 'Service client',
                                    'livraison' => 'Livraison',
                                    'facturation' => 'Facturation',
                                    'autre' => 'Autre'
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $typeClasses[$reclamation->type_reclamation] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $typeLabels[$reclamation->type_reclamation] ?? ucfirst($reclamation->type_reclamation) }}
                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de création</p>
                        <p class="text-base">{{ $reclamation->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Statut</p>
                        <p class="text-base">
                            @php
                                $statusClasses = [
                                    'ouverte' => 'bg-red-100 text-red-800',
                                    'en_cours' => 'bg-yellow-100 text-yellow-800',
                                    'resolue' => 'bg-green-100 text-green-800',
                                    'fermee' => 'bg-gray-100 text-gray-800'
                                ];
                                $statusLabels = [
                                    'ouverte' => 'Ouverte',
                                    'en_cours' => 'En cours',
                                    'resolue' => 'Résolue',
                                    'fermee' => 'Fermée'
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $statusClasses[$reclamation->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$reclamation->statut] ?? ucfirst($reclamation->statut) }}
                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Agent assigné</p>
                        <p class="text-base">
                            @if($reclamation->agent)
                                {{ $reclamation->agent->nom }} {{ $reclamation->agent->prenom }}
                            @else
                                <span class="text-gray-500 italic">Non assigné</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de résolution</p>
                        <p class="text-base">
                            @if($reclamation->date_resolution)
                                {{ \Carbon\Carbon::parse($reclamation->date_resolution)->format('d/m/Y H:i') }}
                            @else
                                <span class="text-gray-500 italic">Non résolue</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-500">Description</p>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                        <p class="text-base whitespace-pre-line">{{ $reclamation->description }}</p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-500">Solution</p>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                        @if($reclamation->solution)
                            <p class="text-base whitespace-pre-line">{{ $reclamation->solution }}</p>
                        @else
                            <p class="text-gray-500 italic">Aucune solution enregistrée</p>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-500">Commentaires</p>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                        @if($reclamation->commentaires)
                            <p class="text-base whitespace-pre-line">{{ $reclamation->commentaires }}</p>
                        @else
                            <p class="text-gray-500 italic">Aucun commentaire</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Actions</h2>
                
                <div class="space-y-3">
                    <form action="{{ route('client-reclamations.change-status', $reclamation) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <div class="mb-2">
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Changer le statut</label>
                            <select name="statut" id="statut" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                                <option value="ouverte" {{ $reclamation->statut == 'ouverte' ? 'selected' : '' }}>Ouverte</option>
                                <option value="en_cours" {{ $reclamation->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="resolue" {{ $reclamation->statut == 'resolue' ? 'selected' : '' }}>Résolue</option>
                                <option value="fermee" {{ $reclamation->statut == 'fermee' ? 'selected' : '' }}>Fermée</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-check-circle mr-2"></i> Mettre à jour le statut
                        </button>
                    </form>
                    
                    <form action="{{ route('client-reclamations.assign-agent', $reclamation) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <div class="mb-2">
                            <label for="agent_id" class="block text-sm font-medium text-gray-700 mb-1">Assigner à un agent</label>
                            <select name="agent_id" id="agent_id" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                                <option value="">Sélectionner un agent</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ $reclamation->agent_id == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->nom }} {{ $agent->prenom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-user-check mr-2"></i> Assigner l'agent
                        </button>
                    </form>
                    
                    <form action="{{ route('client-reclamations.destroy', $reclamation) }}" method="POST" class="w-full" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-trash-alt mr-2"></i> Supprimer la réclamation
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Documents -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Documents</h2>
                
                @if($reclamation->documents->count() > 0)
                    <div class="space-y-3">
                        @foreach($reclamation->documents as $document)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                <div class="flex items-center">
                                    @php
                                        $icon = 'fa-file';
                                        if(in_array($document->format, ['jpg', 'jpeg', 'png', 'gif'])) {
                                            $icon = 'fa-file-image';
                                        } elseif(in_array($document->format, ['pdf'])) {
                                            $icon = 'fa-file-pdf';
                                        } elseif(in_array($document->format, ['doc', 'docx'])) {
                                            $icon = 'fa-file-word';
                                        } elseif(in_array($document->format, ['xls', 'xlsx'])) {
                                            $icon = 'fa-file-excel';
                                        }
                                    @endphp
                                    <i class="fas {{ $icon }} text-gray-500 mr-3 text-xl"></i>
                                    <div>
                                        <p class="text-sm font-medium">{{ $document->nom }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format($document->taille / 1024, 2) }} KB · {{ strtoupper($document->format) }}</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('documents.show', $document) }}" target="_blank" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('documents.download', $document) }}" class="text-green-500 hover:text-green-700">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic">Aucun document attaché</p>
                @endif
                
                <div class="mt-4">
                    <form action="{{ route('client-reclamations.upload-document', $reclamation) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <label for="document" class="block text-sm font-medium text-gray-700 mb-1">Ajouter un document</label>
                            <input type="file" name="document" id="document" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                        <div class="mb-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <input type="text" name="description" id="description" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-upload mr-2"></i> Télécharger
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection