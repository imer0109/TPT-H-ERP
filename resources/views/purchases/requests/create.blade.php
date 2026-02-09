@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Nouvelle Demande d'Achat</h1>
                <p class="text-gray-600 mt-1">Créer une nouvelle demande d'achat (DA)</p>
            </div>
            <a href="{{ route('purchases.requests.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('purchases.requests.store') }}" id="purchase-request-form">
        @csrf

        <!-- Informations générales -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations Générales</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">Société <span class="text-red-500">*</span></label>
                    <select name="company_id" id="company_id" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner une société</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="agency_id" class="block text-sm font-medium text-gray-700 mb-1">Agence</label>
                    <select name="agency_id" id="agency_id" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner une agence</option>
                        @foreach($agencies as $agency)
                            <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>
                                {{ $agency->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('agency_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nature_achat" class="block text-sm font-medium text-gray-700 mb-1">Nature de l'achat <span class="text-red-500">*</span></label>
                    <select name="nature_achat" id="nature_achat" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner la nature</option>
                        <option value="Bien" {{ old('nature_achat') == 'Bien' ? 'selected' : '' }}>Bien</option>
                        <option value="Service" {{ old('nature_achat') == 'Service' ? 'selected' : '' }}>Service</option>
                    </select>
                    @error('nature_achat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_echeance_souhaitee" class="block text-sm font-medium text-gray-700 mb-1">Date d'échéance souhaitée</label>
                    <input type="date" name="date_echeance_souhaitee" id="date_echeance_souhaitee" 
                           value="{{ old('date_echeance_souhaitee') }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    @error('date_echeance_souhaitee')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="designation" class="block text-sm font-medium text-gray-700 mb-1">Désignation <span class="text-red-500">*</span></label>
                    <input type="text" name="designation" id="designation" required 
                           value="{{ old('designation') }}"
                           placeholder="Résumé de la demande d'achat"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    @error('designation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="justification" class="block text-sm font-medium text-gray-700 mb-1">Justification / Besoin <span class="text-red-500">*</span></label>
                    <textarea name="justification" id="justification" required rows="3"
                              placeholder="Expliquer le besoin et la justification de cette demande"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">{{ old('justification') }}</textarea>
                    @error('justification')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fournisseur_suggere_id" class="block text-sm font-medium text-gray-700 mb-1">Fournisseur suggéré</label>
                    <select name="fournisseur_suggere_id" id="fournisseur_suggere_id" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Aucun fournisseur suggéré</option>
                        @foreach($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id }}" {{ old('fournisseur_suggere_id') == $fournisseur->id ? 'selected' : '' }}>
                                {{ $fournisseur->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('fournisseur_suggere_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="2"
                              placeholder="Notes supplémentaires"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Articles/Services -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Articles / Services</h2>
                <button type="button" id="add-item" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Ajouter un article
                </button>
            </div>

            <div id="items-container">
                @if(old('items'))
                    @foreach(old('items') as $index => $item)
                        <div class="item-row border rounded-lg p-4 mb-4 bg-gray-50" data-index="{{ $index }}">
                            @include('purchases.requests._item_form', ['index' => $index, 'item' => $item])
                        </div>
                    @endforeach
                @else
                    <div class="item-row border rounded-lg p-4 mb-4 bg-gray-50" data-index="0">
                        @include('purchases.requests._item_form', ['index' => 0, 'item' => null])
                    </div>
                @endif
            </div>

            <!-- Total estimé -->
            <div class="border-t pt-4 mt-4">
                <div class="flex justify-end">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total estimé</p>
                        <p class="text-xl font-bold text-gray-900" id="total-estimate">0 FCFA</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-end space-x-4">
                <a href="{{ route('purchases.requests.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition">
                    Annuler
                </a>
                <button type="submit" name="action" value="save_draft"
                        class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Enregistrer en brouillon
                </button>
                <button type="submit" name="action" value="submit_for_validation"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-paper-plane mr-2"></i>Soumettre pour validation
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Template pour nouvel article -->
<template id="item-template">
    <div class="item-row border rounded-lg p-4 mb-4 bg-gray-50" data-index="">
        @include('purchases.requests._item_form', ['index' => '__INDEX__', 'item' => null])
    </div>
</template>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ old('items') ? count(old('items')) : 1 }};
    
    // Ajouter un nouvel article
    document.getElementById('add-item').addEventListener('click', function() {
        const template = document.getElementById('item-template');
        const container = document.getElementById('items-container');
        
        let newItem = template.content.cloneNode(true);
        newItem.querySelector('.item-row').setAttribute('data-index', itemIndex);
        
        // Remplacer les placeholders d'index
        const html = newItem.querySelector('.item-row').outerHTML.replace(/__INDEX__/g, itemIndex);
        newItem.querySelector('.item-row').outerHTML = html;
        
        container.appendChild(newItem);
        itemIndex++;
        
        updateTotal();
    });
    
    // Supprimer un article
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const itemRow = e.target.closest('.item-row');
            if (document.querySelectorAll('.item-row').length > 1) {
                itemRow.remove();
                updateTotal();
            } else {
                alert('Vous devez avoir au moins un article.');
            }
        }
    });
    
    // Calculer le total automatiquement
    document.addEventListener('input', function(e) {
        if (e.target.name && (e.target.name.includes('[quantite]') || e.target.name.includes('[prix_unitaire_estime]'))) {
            updateItemTotal(e.target);
            updateTotal();
        }
    });
    
    function updateItemTotal(input) {
        const row = input.closest('.item-row');
        const quantite = row.querySelector('[name*="[quantite]"]').value || 0;
        const prix = row.querySelector('[name*="[prix_unitaire_estime]"]').value || 0;
        const total = quantite * prix;
        
        const totalElement = row.querySelector('.item-total');
        if (totalElement) {
            totalElement.textContent = formatCurrency(total);
        }
    }
    
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(function(row) {
            const quantite = row.querySelector('[name*="[quantite]"]').value || 0;
            const prix = row.querySelector('[name*="[prix_unitaire_estime]"]').value || 0;
            total += quantite * prix;
        });
        
        document.getElementById('total-estimate').textContent = formatCurrency(total);
    }
    
    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
    }
    
    // Calcul initial
    updateTotal();
});
</script>
@endpush
@endsection