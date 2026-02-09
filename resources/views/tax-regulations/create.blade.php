@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Nouvelle Réglementation Fiscale</h1>
        <a href="{{ route('tax-regulations.index') }}" class="text-gray-600 hover:text-gray-900">
            Retour à la liste
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('tax-regulations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-4">
                {{-- Entity Selection --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Entité</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="company_id" class="block text-sm font-medium text-gray-700">Société</label>
                            <select name="company_id" id="company_id"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="">Sélectionner une société</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', $companyId) == $company->id ? 'selected' : '' }}>
                                        {{ $company->raison_sociale }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="agency_id" class="block text-sm font-medium text-gray-700">Agence</label>
                            <select name="agency_id" id="agency_id"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="">Sélectionner une agence</option>
                                @foreach($agencies as $agency)
                                    <option value="{{ $agency->id }}" {{ old('agency_id', $agencyId) == $agency->id ? 'selected' : '' }}>
                                        {{ $agency->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agency_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @error('entity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tax Type --}}
                <div>
                    <label for="tax_type" class="block text-sm font-medium text-gray-700">
                        Type de Taxe <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tax_type" id="tax_type"
                           value="{{ old('tax_type') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    @error('tax_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rate --}}
                <div>
                    <label for="rate" class="block text-sm font-medium text-gray-700">
                        Taux (%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.0001" name="rate" id="rate"
                           value="{{ old('rate') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    @error('rate')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Effective Date --}}
                <div>
                    <label for="effective_date" class="block text-sm font-medium text-gray-700">Date d'Effet</label>
                    <input type="date" name="effective_date" id="effective_date"
                           value="{{ old('effective_date') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md">
                    @error('effective_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Expiry Date --}}
                <div>
                    <label for="expiry_date" class="block text-sm font-medium text-gray-700">Date d'Expiration</label>
                    <input type="date" name="expiry_date" id="expiry_date"
                           value="{{ old('expiry_date') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md">
                    @error('expiry_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="is_active" id="is_active"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-white font-semibold py-2 px-6 rounded-lg shadow">
                    Créer la réglementation fiscale
                </button>
            </div>
        </form>
    </div>
</div>
@endsection