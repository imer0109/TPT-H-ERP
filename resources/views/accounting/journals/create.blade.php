@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Nouveau Journal Comptable</h1>
                <p class="text-gray-600 mt-1">Créer un nouveau journal comptable</p>
            </div>
            <div>
                <a href="{{ route('accounting.journals.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire de création -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('accounting.journals.store') }}" class="space-y-6">
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

                <!-- Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: CA, BNK, VTE, etc."
                           maxlength="10">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nom -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: Journal de Caisse, Journal de Ventes, etc."
                           maxlength="255">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type de journal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de journal <span class="text-red-500">*</span></label>
                    <select name="journal_type" id="journal_type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un type</option>
                        @foreach(\App\Models\AccountingJournal::JOURNAL_TYPES as $key => $value)
                            <option value="{{ $key }}" {{ old('journal_type') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('journal_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Description du journal...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Préfixe de numérotation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Préfixe de numérotation</label>
                    <input type="text" name="number_prefix" id="number_prefix" value="{{ old('number_prefix') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: CA, BNK, etc."
                           maxlength="10">
                    @error('number_prefix')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Compte de débit par défaut -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Compte de débit par défaut</label>
                    <select name="default_debit_account_id" id="default_debit_account_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un compte</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('default_debit_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->code }} - {{ $account->label }}
                            </option>
                        @endforeach
                    </select>
                    @error('default_debit_account_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Compte de crédit par défaut -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Compte de crédit par défaut</label>
                    <select name="default_credit_account_id" id="default_credit_account_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un compte</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('default_credit_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->code }} - {{ $account->label }}
                            </option>
                        @endforeach
                    </select>
                    @error('default_credit_account_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Options -->
                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Validation requise -->
                        <div class="flex items-center">
                            <input type="checkbox" name="requires_validation" id="requires_validation" value="1" 
                                   {{ old('requires_validation', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label for="requires_validation" class="ml-2 block text-sm text-gray-700">
                                Validation requise
                            </label>
                        </div>

                        <!-- Numérotation automatique -->
                        <div class="flex items-center">
                            <input type="checkbox" name="auto_numbering" id="auto_numbering" value="1" 
                                   {{ old('auto_numbering', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label for="auto_numbering" class="ml-2 block text-sm text-gray-700">
                                Numérotation automatique
                            </label>
                        </div>

                        <!-- Statut actif -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Actif
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('accounting.journals.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Créer le journal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection