@extends('layouts.app')

@section('title', 'Créer un Dépôt')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Créer un Nouveau Dépôt</h1>

        <form action="{{ route('stock.warehouses.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4">

                    {{-- Code --}}
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">Code*</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" required
                               class="mt-1 focus:ring-red-500 border py-2 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('code') border-red-500 @enderror">
                        @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nom --}}
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom*</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                               class="mt-1 focus:ring-red-500 border py-2 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('nom') border-red-500 @enderror">
                        @error('nom')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 focus:ring-red-500 focus:border-red-500 block border w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Type du dépôt --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Type*</label>
                        <select name="type" id="type" required
                                class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('type') border-red-500 @enderror">
                            <option value="">Sélectionner un type</option>
                            <option value="principal" {{ old('type') == 'principal' ? 'selected' : '' }}>Principal</option>
                            <option value="secondaire" {{ old('type') == 'secondaire' ? 'selected' : '' }}>Secondaire</option>
                            <option value="production" {{ old('type') == 'production' ? 'selected' : '' }}>Production</option>
                            <option value="logistique" {{ old('type') == 'logistique' ? 'selected' : '' }}>Logistique</option>
                        </select>
                        @error('type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Adresse --}}
                    <div>
                        <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}"
                               class="mt-1 focus:ring-red-500 border py-2 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('adresse') border-red-500 @enderror">
                        @error('adresse')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Entité type --}}
                    <div>
                        <label for="entity_type" class="block text-sm font-medium text-gray-700">Type d’entité*</label>
                        <select name="entity_type" id="entity_type" required
                                class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('entity_type') border-red-500 @enderror">
                            <option value="">Sélectionner une entité</option>
                            <option value="App\Models\Company" {{ old('entity_type') == 'App\Models\Company' ? 'selected' : '' }}>Société</option>
                            <option value="App\Models\Agency" {{ old('entity_type') == 'App\Models\Agency' ? 'selected' : '' }}>Agence</option>
                        </select>
                        @error('entity_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Entité ID --}}
                    <div>
                        <label for="entity_id" class="block text-sm font-medium text-gray-700">Entité*</label>
                        <select name="entity_id" id="entity_id" required
                                class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('entity_id') border-red-500 @enderror">
                            <option value="">Sélectionner une entité</option>
                            {{-- Ici tu vas peupler dynamiquement depuis ton controller --}}
                            @foreach($societes as $societe)
                                <option value="{{ $societe->id }}" data-type="App\Models\Company"
                                    {{ (old('entity_id') == $societe->id && old('entity_type') == 'App\Models\Company') ? 'selected' : '' }}>
                                    Société - {{ $societe->raison_sociale }}
                                </option>
                            @endforeach
                            @foreach($agences as $agence)
                                <option value="{{ $agence->id }}" data-type="App\Models\Agency"
                                    {{ (old('entity_id') == $agence->id && old('entity_type') == 'App\Models\Agency') ? 'selected' : '' }}>
                                    Agence - {{ $agence->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('entity_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actif --}}
                    <div class="flex items-center">
                        <input type="checkbox" name="actif" id="actif" value="1" 
                               {{ old('actif', '1') == '1' ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="actif" class="ml-2 block text-sm text-gray-700">Actif</label>
                    </div>
                </div>
            </div>

            {{-- Boutons --}}
            <div class="flex justify-end mt-4">
                <a href="{{ route('stock.warehouses.index') }}"
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const entityTypeSelect = document.getElementById('entity_type');
        const entityIdSelect = document.getElementById('entity_id');
        
        // Fonction pour filtrer les options en fonction du type d'entité sélectionné
        function filterEntityOptions() {
            const selectedType = entityTypeSelect.value;
            
            // Cacher toutes les options d'abord
            Array.from(entityIdSelect.options).forEach(option => {
                if (option.value === '') {
                    // Garder l'option par défaut visible
                    option.style.display = '';
                } else {
                    const optionType = option.getAttribute('data-type');
                    option.style.display = (optionType === selectedType) ? '' : 'none';
                }
            });
            
            // Réinitialiser la sélection si le type a changé
            entityIdSelect.value = '';
        }
        
        // Appliquer le filtre au chargement de la page
        filterEntityOptions();
        
        // Appliquer le filtre lorsque le type d'entité change
        entityTypeSelect.addEventListener('change', filterEntityOptions);
    });
</script>
@endpush