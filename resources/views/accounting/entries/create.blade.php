@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Nouvelle Écriture Comptable</h1>
                <p class="text-gray-600 mt-1">Créer une nouvelle écriture comptable</p>
            </div>
            <div>
                <a href="{{ route('accounting.entries.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire de création -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('accounting.entries.store') }}" class="space-y-6">
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

                <!-- Agence -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agence</label>
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
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Journal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Journal <span class="text-red-500">*</span></label>
                    <select name="journal_id" id="journal_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un journal</option>
                        @foreach($journals as $journal)
                            <option value="{{ $journal->id }}" {{ old('journal_id') == $journal->id ? 'selected' : '' }}>
                                {{ $journal->code }} - {{ $journal->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('journal_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date d'écriture -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date d'écriture <span class="text-red-500">*</span></label>
                    <input type="date" name="entry_date" id="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    @error('entry_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                    <input type="text" name="description" id="description" value="{{ old('description') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: Vente de marchandises, Paiement fournisseur, etc.">
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Compte de débit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Compte de débit <span class="text-red-500">*</span></label>
                    <select name="debit_account_id" id="debit_account_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un compte</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('debit_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->code }} - {{ $account->label }}
                            </option>
                        @endforeach
                    </select>
                    @error('debit_account_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Compte de crédit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Compte de crédit <span class="text-red-500">*</span></label>
                    <select name="credit_account_id" id="credit_account_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un compte</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('credit_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->code }} - {{ $account->label }}
                            </option>
                        @endforeach
                    </select>
                    @error('credit_account_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Montant -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0.01" step="0.01"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: 100000.00">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Devise -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Devise <span class="text-red-500">*</span></label>
                    <select name="currency" id="currency" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="FCFA" {{ old('currency') == 'FCFA' ? 'selected' : '' }}>FCFA</option>
                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                    </select>
                    @error('currency')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Centre de coût -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Centre de coût</label>
                    <select name="cost_center_id" id="cost_center_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un centre de coût</option>
                        @foreach($costCenters as $costCenter)
                            <option value="{{ $costCenter->id }}" {{ old('cost_center_id') == $costCenter->id ? 'selected' : '' }}>
                                {{ $costCenter->code }} - {{ $costCenter->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('cost_center_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Projet -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Projet</label>
                    <select name="project_id" id="project_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un projet</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->code }} - {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Taux de TVA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Taux de TVA (%)</label>
                    <input type="number" name="vat_rate" id="vat_rate" value="{{ old('vat_rate') }}" min="0" max="100" step="0.01"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: 19.25">
                    @error('vat_rate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Numéro de référence -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de référence</label>
                    <input type="text" name="reference_number" id="reference_number" value="{{ old('reference_number') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: FACT-2023-001">
                    @error('reference_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Informations supplémentaires...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('accounting.entries.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition">
                    Annuler
                </a>
                <button type="submit" name="validate" value="1"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-check mr-2"></i>Valider
                </button>
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Enregistrer comme brouillon
                </button>
            </div>
        </form>
    </div>
</div>
@endsection