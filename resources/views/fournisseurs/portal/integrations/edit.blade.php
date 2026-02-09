@extends('fournisseurs.portal.layout')

@section('title', 'Modifier Intégration - Portail Fournisseur')

@section('header', 'Modifier l'intégration')

@section('content')
<div class="mb-6">
    <a href="{{ route('supplier.portal.integrations.index') }}" 
       class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800">
        <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
    </a>
</div>

<div class="rounded-lg bg-white p-6 shadow">
    <form action="{{ route('supplier.portal.integrations.update', $integration) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="integration_type" class="block text-sm font-medium text-gray-700 mb-1">Type d'Intégration *</label>
                <select name="integration_type" id="integration_type" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">Sélectionner un type</option>
                    <option value="erp" {{ old('integration_type', $integration->integration_type) == 'erp' ? 'selected' : '' }}>ERP</option>
                    <option value="accounting" {{ old('integration_type', $integration->integration_type) == 'accounting' ? 'selected' : '' }}>Comptabilité</option>
                    <option value="inventory" {{ old('integration_type', $integration->integration_type) == 'inventory' ? 'selected' : '' }}>Gestion de stock</option>
                    <option value="custom" {{ old('integration_type', $integration->integration_type) == 'custom' ? 'selected' : '' }}>Personnalisé</option>
                </select>
                @error('integration_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="external_system" class="block text-sm font-medium text-gray-700 mb-1">Système Externe *</label>
                <input type="text" name="external_system" id="external_system" required
                       value="{{ old('external_system', $integration->external_system) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                       placeholder="Ex: SAP, Oracle, QuickBooks...">
                @error('external_system')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="external_id" class="block text-sm font-medium text-gray-700 mb-1">ID Externe</label>
                <input type="text" name="external_id" id="external_id"
                       value="{{ old('external_id', $integration->external_id) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                       placeholder="ID dans le système externe">
                @error('external_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $integration->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Intégration active
                    </label>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <a href="{{ route('supplier.portal.integrations.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                Annuler
            </a>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-save mr-2"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>

@if($integration->sync_error_message)
<div class="mt-6 rounded-lg bg-red-50 p-6 shadow-md">
    <h2 class="mb-4 text-lg font-bold text-red-800">Erreur de Synchronisation</h2>
    <div class="prose max-w-none text-red-700">
        <p>{{ $integration->sync_error_message }}</p>
    </div>
</div>
@endif

<!-- Integration Information -->
<div class="mt-6 rounded-lg bg-primary-50 p-6 shadow-md">
    <h2 class="mb-4 text-lg font-bold text-gray-800">Informations sur les intégrations</h2>
    <div class="prose max-w-none">
        <p>Les intégrations permettent de synchroniser les données de votre fournisseur avec des systèmes externes tels que :</p>
        <ul class="list-disc pl-5">
            <li><strong>ERP</strong> : Intégration avec des systèmes de gestion des ressources de l'entreprise (SAP, Oracle, etc.)</li>
            <li><strong>Comptabilité</strong> : Synchronisation des données comptables et financières (QuickBooks, Sage, etc.)</li>
            <li><strong>Gestion de stock</strong> : Synchronisation des niveaux de stock et des mouvements</li>
            <li><strong>Personnalisé</strong> : Intégrations spécifiques selon vos besoins</li>
        </ul>
        <p class="mt-3"><strong>Note :</strong> La synchronisation peut être effectuée manuellement ou automatiquement selon la configuration du système externe.</p>
    </div>
</div>
@endsection