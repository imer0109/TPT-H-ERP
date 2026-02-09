@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Modifier la Réclamation #{{ $reclamation->id }}</h1>
        <div>
            <a href="{{ route('client-reclamations.show', $reclamation) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour aux détails
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('client-reclamations.update', $reclamation) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informations de base</h2>
                </div>

                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client <span class="text-red-600">*</span></label>
                    <select name="client_id" id="client_id" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $reclamation->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->nom_raison_sociale }} ({{ $client->code_client }})
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type_reclamation" class="block text-sm font-medium text-gray-700 mb-1">Type de réclamation <span class="text-red-600">*</span></label>
                    <select name="type_reclamation" id="type_reclamation" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un type</option>
                        <option value="qualite_produit" {{ old('type_reclamation', $reclamation->type_reclamation) == 'qualite_produit' ? 'selected' : '' }}>Qualité produit</option>
                        <option value="service_client" {{ old('type_reclamation', $reclamation->type_reclamation) == 'service_client' ? 'selected' : '' }}>Service client</option>
                        <option value="livraison" {{ old('type_reclamation', $reclamation->type_reclamation) == 'livraison' ? 'selected' : '' }}>Livraison</option>
                        <option value="facturation" {{ old('type_reclamation', $reclamation->type_reclamation) == 'facturation' ? 'selected' : '' }}>Facturation</option>
                        <option value="autre" {{ old('type_reclamation', $reclamation->type_reclamation) == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type_reclamation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-600">*</span></label>
                    <textarea name="description" id="description" rows="4" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">{{ old('description', $reclamation->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Traitement -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 mt-4">Traitement</h2>
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut <span class="text-red-600">*</span></label>
                    <select name="statut" id="statut" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="ouverte" {{ old('statut', $reclamation->statut) == 'ouverte' ? 'selected' : '' }}>Ouverte</option>
                        <option value="en_cours" {{ old('statut', $reclamation->statut) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="resolue" {{ old('statut', $reclamation->statut) == 'resolue' ? 'selected' : '' }}>Résolue</option>
                        <option value="fermee" {{ old('statut', $reclamation->statut) == 'fermee' ? 'selected' : '' }}>Fermée</option>
                    </select>
                    @error('statut')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="agent_id" class="block text-sm font-medium text-gray-700 mb-1">Agent assigné</label>
                    <select name="agent_id" id="agent_id" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un agent</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ old('agent_id', $reclamation->agent_id) == $agent->id ? 'selected' : '' }}>
                                {{ $agent->nom }} {{ $agent->prenom }}
                            </option>
                        @endforeach
                    </select>
                    @error('agent_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_resolution" class="block text-sm font-medium text-gray-700 mb-1">Date de résolution</label>
                    <input type="datetime-local" name="date_resolution" id="date_resolution" 
                        value="{{ old('date_resolution', $reclamation->date_resolution ? date('Y-m-d\TH:i', strtotime($reclamation->date_resolution)) : '') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    @error('date_resolution')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="solution" class="block text-sm font-medium text-gray-700 mb-1">Solution</label>
                    <textarea name="solution" id="solution" rows="3" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">{{ old('solution', $reclamation->solution) }}</textarea>
                    @error('solution')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="commentaires" class="block text-sm font-medium text-gray-700 mb-1">Commentaires</label>
                    <textarea name="commentaires" id="commentaires" rows="3" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">{{ old('commentaires', $reclamation->commentaires) }}</textarea>
                    @error('commentaires')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Documents -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 mt-4">Documents</h2>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Documents existants</label>
                    
                    @if($reclamation->documents->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
                                        <a href="{{ route('documents.show', $document) }}" target="_blank" class="text-primary-500 hover:text-primary-700">
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
                        <p class="text-gray-500 italic mb-4">Aucun document attaché</p>
                    @endif
                    
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ajouter de nouveaux documents (PDF, JPG, PNG)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="document_preuve" class="block text-sm font-medium text-gray-700 mb-1">Preuve de réclamation</label>
                            <input type="file" name="documents[preuve]" id="document_preuve" accept=".pdf,.jpg,.jpeg,.png" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                        <div>
                            <label for="document_autre" class="block text-sm font-medium text-gray-700 mb-1">Autre document</label>
                            <input type="file" name="documents[autre]" id="document_autre" accept=".pdf,.jpg,.jpeg,.png" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                    </div>
                    @error('documents')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('client-reclamations.show', $reclamation) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection