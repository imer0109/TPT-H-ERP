@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nouvelle Intégration Client</h1>
        <a href="{{ route('clients.show', $client) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Retour au Client
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('clients.integrations.store', $client) }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="client_info" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <div class="font-medium">{{ $client->nom_raison_sociale }}</div>
                        <div class="text-sm text-gray-500">{{ $client->code_client }}</div>
                    </div>
                </div>
                
                <div>
                    <label for="integration_type" class="block text-sm font-medium text-gray-700 mb-1">Type d'Intégration *</label>
                    <select name="integration_type" id="integration_type" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        <option value="">Sélectionner un type</option>
                        <option value="crm">CRM</option>
                        <option value="marketing">Marketing</option>
                        <option value="erp">ERP</option>
                    </select>
                    @error('integration_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="external_system" class="block text-sm font-medium text-gray-700 mb-1">Système Externe *</label>
                    <input type="text" name="external_system" id="external_system" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                           placeholder="Ex: Mailchimp, WhatsApp Business, Salesforce...">
                    @error('external_system')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="external_id" class="block text-sm font-medium text-gray-700 mb-1">ID Externe</label>
                    <input type="text" name="external_id" id="external_id"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                           placeholder="ID dans le système externe">
                    @error('external_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked
                               class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Intégration active
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <a href="{{ route('clients.show', $client) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Annuler
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Créer l'Intégration
                </button>
            </div>
        </form>
    </div>
    
    <!-- Integration Information -->
    <div class="mt-6 bg-primary-50 rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Informations sur les Intégrations</h2>
        <div class="prose max-w-none">
            <p>Les intégrations permettent de synchroniser les données clients avec des systèmes externes tels que :</p>
            <ul class="list-disc pl-5 mt-2">
                <li><strong>CRM</strong> : Synchronisation des données clients avec des systèmes de gestion de la relation client</li>
                <li><strong>Marketing</strong> : Export des segments clients vers des outils de marketing digital (Mailchimp, WhatsApp Business API, etc.)</li>
                <li><strong>ERP</strong> : Intégration avec des systèmes de gestion des ressources de l'entreprise</li>
            </ul>
            <p class="mt-3"><strong>Note :</strong> La synchronisation peut être effectuée manuellement ou automatiquement selon la configuration du système externe.</p>
        </div>
    </div>
</div>
@endsection