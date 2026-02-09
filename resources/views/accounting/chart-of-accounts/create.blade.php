@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Nouveau Compte Comptable</h1>
                <p class="text-gray-600 mt-1">Créer un nouveau compte dans le plan comptable</p>
            </div>
            <div>
                <a href="{{ route('accounting.chart-of-accounts.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire de création -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('accounting.chart-of-accounts.store') }}" class="space-y-6">
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

                <!-- Compte parent -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Compte parent</label>
                    <select name="parent_id" id="parent_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Aucun (compte racine)</option>
                        @if(isset($parent))
                            <option value="{{ $parent->id }}" selected>
                                {{ $parent->code }} - {{ $parent->label }}
                            </option>
                        @endif
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('parent_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->code }} - {{ $account->label }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: 411001">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Libellé -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Libellé <span class="text-red-500">*</span></label>
                    <input type="text" name="label" id="label" value="{{ old('label') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: Clients - Ventes">
                    @error('label')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type de compte -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de compte <span class="text-red-500">*</span></label>
                    <select name="account_type" id="account_type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un type</option>
                        @foreach(\App\Models\ChartOfAccount::ACCOUNT_TYPES as $key => $value)
                            <option value="{{ $key }}" {{ old('account_type') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('account_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nature du compte -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nature du compte <span class="text-red-500">*</span></label>
                    <select name="account_nature" id="account_nature" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner une nature</option>
                        @foreach(\App\Models\ChartOfAccount::ACCOUNT_NATURES as $key => $value)
                            <option value="{{ $key }}" {{ old('account_nature') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('account_nature')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Compte auxiliaire -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_auxiliary" id="is_auxiliary" value="1" 
                           {{ old('is_auxiliary') ? 'checked' : '' }}
                           class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="is_auxiliary" class="ml-2 block text-sm text-gray-700">
                        Compte auxiliaire
                    </label>
                </div>

                <!-- Type auxiliaire -->
                <div id="aux_type_container" class="{{ old('is_auxiliary') ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type auxiliaire</label>
                    <select name="aux_type" id="aux_type"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un type</option>
                        @foreach(\App\Models\ChartOfAccount::AUX_TYPES as $key => $value)
                            <option value="{{ $key }}" {{ old('aux_type') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('aux_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TVA applicable -->
                <div class="flex items-center">
                    <input type="checkbox" name="vat_applicable" id="vat_applicable" value="1" 
                           {{ old('vat_applicable') ? 'checked' : '' }}
                           class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="vat_applicable" class="ml-2 block text-sm text-gray-700">
                        TVA applicable
                    </label>
                </div>

                <!-- Code SYSCOHADA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code SYSCOHADA</label>
                    <input type="text" name="syscohada_code" id="syscohada_code" value="{{ old('syscohada_code') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: 411">
                    @error('syscohada_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Description du compte...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                    <i class="fas fa-save mr-2"></i>Créer le compte
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isAuxiliaryCheckbox = document.getElementById('is_auxiliary');
        const auxTypeContainer = document.getElementById('aux_type_container');
        
        isAuxiliaryCheckbox.addEventListener('change', function() {
            if (this.checked) {
                auxTypeContainer.classList.remove('hidden');
            } else {
                auxTypeContainer.classList.add('hidden');
                document.getElementById('aux_type').value = '';
            }
        });
    });
</script>
@endsection