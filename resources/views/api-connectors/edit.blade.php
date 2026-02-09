@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-plug text-indigo-600"></i>
            Modifier le Connecteur API
        </h3>
        <a href="{{ route('api-connectors.api-connectors.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-xl p-6">
        <form action="{{ route('api-connectors.api-connectors.update', $apiConnector) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Nom du Connecteur <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $apiConnector->name) }}" required
                           class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400"
                           placeholder="Ex: Connecteur Sage Comptabilité">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Société -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Société <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="company_id" required
                                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 pr-8 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400 appearance-none">
                            <option value="">Sélectionner une société</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $apiConnector->company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->raison_sociale }}
                                </option>
                            @endforeach
                        </select>
                        <span class="absolute right-3 top-3 text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    @error('company_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Type de Connecteur <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="type" required
                                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 pr-8 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400 appearance-none">
                            <option value="">Sélectionner un type</option>
                            @foreach($connectorTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('type', $apiConnector->type) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <span class="absolute right-3 top-3 text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400"
                              placeholder="Description du connecteur...">{{ old('description', $apiConnector->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fréquence de Synchronisation -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Fréquence de Synchronisation <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="sync_frequency" required
                                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 pr-8 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400 appearance-none">
                            <option value="">Sélectionner une fréquence</option>
                            @foreach($syncFrequencies as $key => $label)
                                <option value="{{ $key }}" {{ old('sync_frequency', $apiConnector->sync_frequency) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <span class="absolute right-3 top-3 text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    @error('sync_frequency')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut Actif -->
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="is_active" id="is_active" 
                           class="h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500" 
                           {{ old('is_active', $apiConnector->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 block text-gray-700 font-semibold">
                        Connecteur Actif
                    </label>
                </div>
            </div>

            <!-- Configuration (will be expanded based on connector type) -->
            <div class="mt-8 border-t border-gray-200 pt-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Configuration</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- URL de l'API -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            URL de l'API
                        </label>
                        <input type="url" name="configuration[url]" value="{{ old('configuration.url', $apiConnector->getConfig('url')) }}"
                               class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400"
                               placeholder="https://api.example.com">
                        @error('configuration.url')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Clé API -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Clé API
                        </label>
                        <input type="text" name="configuration[api_key]" value="{{ old('configuration.api_key', $apiConnector->getConfig('api_key')) }}"
                               class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400"
                               placeholder="Votre clé API">
                        @error('configuration.api_key')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom d'utilisateur -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Nom d'utilisateur
                        </label>
                        <input type="text" name="configuration[username]" value="{{ old('configuration.username', $apiConnector->getConfig('username')) }}"
                               class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400"
                               placeholder="Nom d'utilisateur">
                        @error('configuration.username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Mot de passe
                        </label>
                        <input type="password" name="configuration[password]" 
                               class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400"
                               placeholder="Mot de passe">
                        @error('configuration.password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('api-connectors.api-connectors.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-save mr-2"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection