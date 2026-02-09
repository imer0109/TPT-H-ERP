@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Import du Plan Comptable</h1>
                <p class="text-gray-600 mt-1">Importer un plan comptable depuis un fichier Excel ou CSV</p>
            </div>
            <div>
                <a href="{{ route('accounting.chart-of-accounts.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Instructions d'import -->
    <div class="bg-primary-50 border border-primary-200 rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-primary-800 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Instructions d'import
        </h2>
        <ul class="list-disc list-inside space-y-2 text-primary-700">
            <li>Le fichier doit être au format Excel (.xlsx, .xls) ou CSV</li>
            <li>La première ligne doit contenir les en-têtes de colonnes</li>
            <li>Colonnes requises : Code, Libellé, Type de compte, Nature du compte</li>
            <li>Les types de compte valides : classe, sous_classe, compte, sous_compte</li>
            <li>Les natures de compte valides : debit, credit</li>
            <li>Vous pouvez inclure des colonnes optionnelles : Compte parent, Compte auxiliaire, Type auxiliaire, TVA applicable, Description, Code SYSCOHADA</li>
        </ul>
        
        <div class="mt-4 p-4 bg-white rounded-lg">
            <h3 class="font-medium text-gray-800 mb-2">Exemple de structure de fichier :</h3>
            <pre class="text-sm text-gray-600 bg-gray-100 p-3 rounded overflow-x-auto">
Code;Libellé;Type de compte;Nature du compte;Compte parent;Compte auxiliaire;Type auxiliaire;TVA applicable;Description;Code SYSCOHADA
411;Clients;compte;debit;;;client;;Clients commerciaux;411
411001;Clients - Ventes;compte;debit;411;;client;;Clients pour ventes;411
521;Banques locales;compte;debit;;;banque;;Comptes bancaires locaux;521
            </pre>
        </div>
    </div>

    <!-- Formulaire d'import -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('accounting.chart-of-accounts.import') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Société -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Société <span class="text-red-500">*</span></label>
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
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fichier -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fichier <span class="text-red-500">*</span></label>
                    <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- En-tête -->
                <div class="flex items-center">
                    <input type="checkbox" name="has_header" id="has_header" value="1" checked
                           class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="has_header" class="ml-2 block text-sm text-gray-700">
                        La première ligne contient les en-têtes
                    </label>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('accounting.chart-of-accounts.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-upload mr-2"></i>Importer
                </button>
            </div>
        </form>
    </div>

    <!-- Modèles de téléchargement -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Télécharger un modèle</h2>
        
        <div class="flex space-x-4">
            <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fas fa-file-excel text-green-600 mr-2"></i>
                Modèle Excel
            </a>
            <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fas fa-file-csv text-primary-600 mr-2"></i>
                Modèle CSV
            </a>
        </div>
    </div>
</div>
@endsection