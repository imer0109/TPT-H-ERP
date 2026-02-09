@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Créer une Nouvelle Caisse</h1>
            <p class="mt-2 text-gray-600">Remplissez les informations ci-dessous</p>
        </div>

        <form action="{{ route('cash.registers.store') }}" method="POST" id="cashRegisterForm">
            @csrf

            <div class="bg-white shadow rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Nom -->
                    <div>
                        <label class="block font-medium">Nom *</label>
                        <input type="text" name="nom" class="w-full border p-2 rounded" required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block font-medium">Description</label>
                        <input type="text" name="description" class="w-full border p-2 rounded">
                    </div>

                    <!-- Type entité -->
                    <div>
                        <label class="block font-medium">Type d'entité *</label>
                        <select id="entity_type" name="entity_type" class="w-full border p-2 rounded" required>
                            <option value="">Sélectionner</option>
                            <option value="App\Models\Company">Société</option>
                            <option value="App\Models\Agency">Agence</option>
                        </select>
                    </div>

                    <!-- Entité -->
                    <div>
                        <label class="block font-medium">Entité *</label>
                        <select id="entity_id" name="entity_id" class="w-full border p-2 rounded" required>
                            <option value="">Sélectionner un type d'entité</option>
                        </select>
                    </div>

                    <!-- Type caisse -->
                    <div>
                        <label class="block font-medium">Type de caisse *</label>
                        <select name="type" class="w-full border p-2 rounded" required>
                            <option value="">Sélectionner</option>
                            <option value="principale">Principale</option>
                            <option value="secondaire">Secondaire</option>
                        </select>
                    </div>

                    <!-- Solde -->
                    <div>
                        <label class="block font-medium">Solde initial *</label>
                        <input type="number" name="solde_initial" value="0" min="0" class="w-full border p-2 rounded" required>
                    </div>

                    <!-- Statut -->
                    <div class="md:col-span-2">
                        <label class="block font-medium">Statut</label>
                        <select name="active" class="w-1/2 border p-2 rounded">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('cash.registers.index') }}" class="px-4 py-2 border rounded">
                    Annuler
                </a>
                <button class="px-6 py-2 bg-red-600 text-white rounded">
                    Créer la caisse
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const entityTypeSelect = document.getElementById('entity_type');
    const entityIdSelect   = document.getElementById('entity_id');

    function loadEntities(entityType) {
        // Réinitialiser la liste des entités
        entityIdSelect.innerHTML = '<option value="">Chargement...</option>';

        // Si aucun type n'est sélectionné, afficher le message par défaut
        if (!entityType) {
            entityIdSelect.innerHTML = '<option value="">Sélectionner un type d'entité</option>';
            return;
        }

        // Appel API pour récupérer les entités
        fetch(`/api/entities-by-type?type=${encodeURIComponent(entityType)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                // Réinitialiser la liste
                entityIdSelect.innerHTML = '<option value="">Sélectionner une entité</option>';
                
                // Vérifier si les données sont dans un objet "data"
                const entities = Array.isArray(data) ? data : (data.data || []);
                
                // Ajouter chaque entité à la liste
                entities.forEach(entity => {
                    const option = document.createElement('option');
                    option.value = entity.id;
                    
                    // Afficher le nom approprié selon le type d'entité
                    if (entityType === 'App\\Models\\Company') {
                        option.textContent = entity.raison_sociale || 'Société sans nom';
                    } else if (entityType === 'App\\Models\\Agency') {
                        option.textContent = entity.nom || 'Agence sans nom';
                    } else {
                        option.textContent = entity.raison_sociale || entity.nom || 'Entité sans nom';
                    }
                    
                    entityIdSelect.appendChild( option);
                });
                
                // Message si aucune entité trouvée
                if (entities.length === 0) {
                    entityIdSelect.innerHTML = '<option value="">Aucune entité trouvée</option>';
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des entités:', error);
                entityIdSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    }

    // Écouter les changements sur le type d'entité
    entityTypeSelect.addEventListener('change', function () {
        loadEntities(this.value);
    });
    
    // Charger les entités si un type est déjà sélectionné (en cas de validation avec erreurs)
    if (entityTypeSelect.value) {
        loadEntities(entityTypeSelect.value);
    }
});
</script>
@endsection
