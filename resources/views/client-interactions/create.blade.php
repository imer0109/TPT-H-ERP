@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nouvelle Interaction Client</h1>
        <div>
            <a href="{{ route('client-interactions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('client-interactions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->nom_raison_sociale }} ({{ $client->code_client }})
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type_interaction" class="block text-sm font-medium text-gray-700 mb-1">Type d'interaction <span class="text-red-600">*</span></label>
                    <select name="type_interaction" id="type_interaction" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un type</option>
                        <option value="appel" {{ old('type_interaction') == 'appel' ? 'selected' : '' }}>Appel</option>
                        <option value="email" {{ old('type_interaction') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="reunion" {{ old('type_interaction') == 'reunion' ? 'selected' : '' }}>Réunion</option>
                        <option value="visite" {{ old('type_interaction') == 'visite' ? 'selected' : '' }}>Visite</option>
                        <option value="autre" {{ old('type_interaction') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type_interaction')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_interaction" class="block text-sm font-medium text-gray-700 mb-1">Date de l'interaction <span class="text-red-600">*</span></label>
                    <input type="datetime-local" name="date_interaction" id="date_interaction" value="{{ old('date_interaction', now()->format('Y-m-d\TH:i')) }}" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    @error('date_interaction')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="campagne_id" class="block text-sm font-medium text-gray-700 mb-1">Campagne</label>
                    <select name="campagne_id" id="campagne_id" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner une campagne</option>
                        @foreach($campagnes as $campagne)
                            <option value="{{ $campagne->id }}" {{ old('campagne_id') == $campagne->id ? 'selected' : '' }}>
                                {{ $campagne->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('campagne_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-600">*</span></label>
                    <textarea name="description" id="description" rows="4" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="resultat" class="block text-sm font-medium text-gray-700 mb-1">Résultat</label>
                    <textarea name="resultat" id="resultat" rows="3" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">{{ old('resultat') }}</textarea>
                    @error('resultat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Suivi -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 mt-4">Suivi</h2>
                </div>

                <div>
                    <div class="flex items-center">
                        <input type="checkbox" name="suivi_necessaire" id="suivi_necessaire" value="1" {{ old('suivi_necessaire') ? 'checked' : '' }} 
                            class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        <label for="suivi_necessaire" class="ml-2 block text-sm font-medium text-gray-700">Suivi nécessaire</label>
                    </div>
                    @error('suivi_necessaire')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_suivi" class="block text-sm font-medium text-gray-700 mb-1">Date de suivi</label>
                    <input type="date" name="date_suivi" id="date_suivi" value="{{ old('date_suivi') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    @error('date_suivi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Documents -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 mt-4">Documents</h2>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Documents (PDF, JPG, PNG)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="document_rapport" class="block text-sm font-medium text-gray-700 mb-1">Rapport d'interaction</label>
                            <input type="file" name="documents[rapport]" id="document_rapport" accept=".pdf,.jpg,.jpeg,.png" 
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
                <button type="reset" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                    <i class="fas fa-undo mr-2"></i> Réinitialiser
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection