@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Modifier la Caisse</h1>

        <form action="{{ route('cash.registers.update', $cashRegister) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom de la caisse</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom', $cashRegister->nom) }}" required
                            class="mt-1 focus:ring-red-500 border py-2 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('nom')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Type de caisse</label>
                        <select name="type" id="type" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                            <option value="principale" {{ old('type', $cashRegister->type) === 'principale' ? 'selected' : '' }}>Principale</option>
                            <option value="secondaire" {{ old('type', $cashRegister->type) === 'secondaire' ? 'selected' : '' }}>Secondaire</option>
                        </select>
                        @error('type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="entity_type" class="block text-sm font-medium text-gray-700">Type d'entité</label>
                        <select name="entity_type" id="entity_type" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                            <option value="">Sélectionner un type d'entité</option>
                            <option value="App\Models\Company" {{ old('entity_type', $cashRegister->entity_type) === 'App\Models\Company' ? 'selected' : '' }}>Société</option>
                            <option value="App\Models\Agency" {{ old('entity_type', $cashRegister->entity_type) === 'App\Models\Agency' ? 'selected' : '' }}>Agence</option>
                        </select>
                        @error('entity_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="societe_select" class="{{ old('entity_type', $cashRegister->entity_type) === 'App\Models\Company' ? '' : 'hidden' }}">
                        <label for="societe_id" class="block text-sm font-medium text-gray-700">Société</label>
                        <select name="entity_id" id="societe_id"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                            <option value="">Sélectionner une société</option>
                            @foreach($societes as $societe)
                            <option value="{{ $societe->id }}" {{ (old('entity_id', $cashRegister->entity_id) == $societe->id && old('entity_type', $cashRegister->entity_type) === 'App\Models\Company') ? 'selected' : '' }}>{{ $societe->raison_sociale }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="agence_select" class="{{ old('entity_type', $cashRegister->entity_type) === 'App\Models\Agency' ? '' : 'hidden' }}">
                        <label for="agence_id" class="block text-sm font-medium text-gray-700">Agence</label>
                        <select name="entity_id" id="agence_id"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                            <option value="">Sélectionner une agence</option>
                            @foreach($agences as $agence)
                            <option value="{{ $agence->id }}" {{ (old('entity_id', $cashRegister->entity_id) == $agence->id && old('entity_type', $cashRegister->entity_type) === 'App\Models\Agency') ? 'selected' : '' }}>{{ $agence->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('entity_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div>
                        <p class="block text-sm font-medium text-gray-700">Solde actuel: {{ number_format($cashRegister->solde_actuel, 2, ',', ' ') }} FCFA</p>
                        <p class="text-sm text-gray-500 mt-1">Le solde ne peut être modifié que lors des opérations d'ouverture/fermeture de caisse ou des transactions.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('cash.registers.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Annuler
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const entityTypeSelect = document.getElementById('entity_type');
        const societeSelect = document.getElementById('societe_select');
        const agenceSelect = document.getElementById('agence_select');
        const societeIdSelect = document.getElementById('societe_id');
        const agenceIdSelect = document.getElementById('agence_id');
        
        // Initialiser l'état des sélections au chargement de la page
        if (entityTypeSelect.value === 'App\\Models\\Company') {
            societeSelect.classList.remove('hidden');
            agenceSelect.classList.add('hidden');
            // Assurez-vous que seul le bon select a le nom "entity_id"
            agenceIdSelect.removeAttribute('name');
            societeIdSelect.setAttribute('name', 'entity_id');
        } else if (entityTypeSelect.value === 'App\\Models\\Agency') {
            societeSelect.classList.add('hidden');
            agenceSelect.classList.remove('hidden');
            // Assurez-vous que seul le bon select a le nom "entity_id"
            societeIdSelect.removeAttribute('name');
            agenceIdSelect.setAttribute('name', 'entity_id');
        } else {
            societeSelect.classList.add('hidden');
            agenceSelect.classList.add('hidden');
            // Supprimez les noms si aucun type n'est sélectionné
            societeIdSelect.removeAttribute('name');
            agenceIdSelect.removeAttribute('name');
        }

        entityTypeSelect.addEventListener('change', function() {
            if (this.value === 'App\\Models\\Company') {
                societeSelect.classList.remove('hidden');
                agenceSelect.classList.add('hidden');
                // Assurez-vous que seul le bon select a le nom "entity_id"
                agenceIdSelect.removeAttribute('name');
                societeIdSelect.setAttribute('name', 'entity_id');
            } else if (this.value === 'App\\Models\\Agency') {
                societeSelect.classList.add('hidden');
                agenceSelect.classList.remove('hidden');
                // Assurez-vous que seul le bon select a le nom "entity_id"
                societeIdSelect.removeAttribute('name');
                agenceIdSelect.setAttribute('name', 'entity_id');
            } else {
                societeSelect.classList.add('hidden');
                agenceSelect.classList.add('hidden');
                // Supprimez les noms si aucun type n'est sélectionné
                societeIdSelect.removeAttribute('name');
                agenceIdSelect.removeAttribute('name');
            }
        });
    });
</script>
@endpush
@endsection