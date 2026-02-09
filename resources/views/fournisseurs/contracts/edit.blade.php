@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Modifier le Contrat: {{ $contract->contract_number }}</h1>
        <a href="{{ route('fournisseurs.contracts.show', $contract) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Retour au contrat
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('fournisseurs.contracts.update', $contract) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Fournisseur -->
                <div>
                    <label for="fournisseur_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Fournisseur <span class="text-red-600">*</span>
                    </label>
                    <select name="fournisseur_id" id="fournisseur_id" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un fournisseur</option>
                        @foreach($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id', $contract->fournisseur_id) == $fournisseur->id ? 'selected' : '' }}>
                                {{ $fournisseur->raison_sociale }}
                            </option>
                        @endforeach
                    </select>
                    @error('fournisseur_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type de contrat -->
                <div>
                    <label for="contract_type" class="block text-sm font-medium text-gray-700 mb-1">
                        Type de contrat <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="contract_type" id="contract_type" value="{{ old('contract_type', $contract->contract_type) }}" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    @error('contract_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">{{ old('description', $contract->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de début -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Date de début <span class="text-red-600">*</span>
                    </label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    @error('start_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de fin -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Date de fin <span class="text-red-600">*</span>
                    </label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $contract->end_date->format('Y-m-d')) }}" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    @error('end_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de renouvellement -->
                <div>
                    <label for="renewal_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Date de renouvellement
                    </label>
                    <input type="date" name="renewal_date" id="renewal_date" value="{{ old('renewal_date', $contract->renewal_date ? $contract->renewal_date->format('Y-m-d') : '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    @error('renewal_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Renouvellement automatique -->
                <div class="flex items-center">
                    <input type="checkbox" name="auto_renewal" id="auto_renewal" value="1"
                        class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                        {{ old('auto_renewal', $contract->auto_renewal) ? 'checked' : '' }}>
                    <label for="auto_renewal" class="ml-2 block text-sm text-gray-700">
                        Renouvellement automatique
                    </label>
                </div>

                <!-- Valeur -->
                <div>
                    <label for="value" class="block text-sm font-medium text-gray-700 mb-1">
                        Valeur du contrat
                    </label>
                    <input type="number" name="value" id="value" value="{{ old('value', $contract->value) }}" step="0.01"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    @error('value')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Devise -->
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">
                        Devise
                    </label>
                    <select name="currency" id="currency"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="XOF" {{ old('currency', $contract->currency) == 'XOF' ? 'selected' : '' }}>XOF (Franc CFA)</option>
                        <option value="EUR" {{ old('currency', $contract->currency) == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                        <option value="USD" {{ old('currency', $contract->currency) == 'USD' ? 'selected' : '' }}>USD (Dollar US)</option>
                    </select>
                    @error('currency')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Responsable -->
                <div>
                    <label for="responsible_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Responsable du contrat
                    </label>
                    <select name="responsible_id" id="responsible_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un responsable</option>
                        @foreach($responsibles as $responsible)
                            <option value="{{ $responsible->id }}" {{ old('responsible_id', $contract->responsible_id) == $responsible->id ? 'selected' : '' }}>
                                {{ $responsible->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('responsible_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Conditions générales -->
                <div class="md:col-span-2">
                    <label for="terms" class="block text-sm font-medium text-gray-700 mb-1">
                        Conditions générales
                    </label>
                    <textarea name="terms" id="terms" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">{{ old('terms', $contract->terms) }}</textarea>
                    @error('terms')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Conditions spéciales -->
                <div class="md:col-span-2">
                    <label for="special_conditions" class="block text-sm font-medium text-gray-700 mb-1">
                        Conditions spéciales
                    </label>
                    <textarea name="special_conditions" id="special_conditions" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">{{ old('special_conditions', $contract->special_conditions) }}</textarea>
                    @error('special_conditions')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                        Notes internes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">{{ old('notes', $contract->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <a href="{{ route('fournisseurs.contracts.show', $contract) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Mettre à jour le contrat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection