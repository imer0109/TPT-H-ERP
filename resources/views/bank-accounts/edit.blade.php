@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Modifier le Compte Bancaire</h1>
        <a href="{{ route('bank-accounts.index') }}" class="text-gray-600 hover:text-gray-900">
            Retour à la liste
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('bank-accounts.update', $bankAccount) }}" method="POST">
            @csrf
            @method('PUT')

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
                                    <option value="{{ $company->id }}" {{ old('company_id', $bankAccount->company_id) == $company->id ? 'selected' : '' }}>
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
                                    <option value="{{ $agency->id }}" {{ old('agency_id', $bankAccount->agency_id) == $agency->id ? 'selected' : '' }}>
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

                {{-- Bank Name --}}
                <div>
                    <label for="bank_name" class="block text-sm font-medium text-gray-700">
                        Nom de la Banque <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="bank_name" id="bank_name"
                           value="{{ old('bank_name', $bankAccount->bank_name) }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    @error('bank_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Account Number --}}
                <div>
                    <label for="account_number" class="block text-sm font-medium text-gray-700">
                        Numéro de Compte <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="account_number" id="account_number"
                           value="{{ old('account_number', $bankAccount->account_number) }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    @error('account_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- IBAN --}}
                <div>
                    <label for="iban" class="block text-sm font-medium text-gray-700">IBAN</label>
                    <input type="text" name="iban" id="iban"
                           value="{{ old('iban', $bankAccount->iban) }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md">
                    @error('iban')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- BIC/SWIFT --}}
                <div>
                    <label for="bic_swift" class="block text-sm font-medium text-gray-700">BIC/SWIFT</label>
                    <input type="text" name="bic_swift" id="bic_swift"
                           value="{{ old('bic_swift', $bankAccount->bic_swift) }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md">
                    @error('bic_swift')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Currency --}}
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700">
                        Devise <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="currency" id="currency"
                           value="{{ old('currency', $bankAccount->currency) }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    @error('currency')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Account Type --}}
                <div>
                    <label for="account_type" class="block text-sm font-medium text-gray-700">
                        Type de Compte <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="account_type" id="account_type"
                           value="{{ old('account_type', $bankAccount->account_type) }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    @error('account_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Balance --}}
                <div>
                    <label for="balance" class="block text-sm font-medium text-gray-700">Solde</label>
                    <input type="number" step="0.01" name="balance" id="balance"
                           value="{{ old('balance', $bankAccount->balance) }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md">
                    @error('balance')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="is_active" id="is_active"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="1" {{ old('is_active', $bankAccount->is_active) == 1 ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('is_active', $bankAccount->is_active) == 0 ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-white font-semibold py-2 px-6 rounded-lg shadow">
                    Mettre à jour le compte bancaire
                </button>
            </div>
        </form>
    </div>
</div>
@endsection