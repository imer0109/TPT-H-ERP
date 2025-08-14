@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Nouvelle Transaction</h1>
            <a href="{{ route('cash.registers.show', ['cashRegister' => $cashRegister->id]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                Retour à la caisse
            </a>
        </div>

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Caisse</p>
                            <p class="text-lg font-semibold">{{ $cashRegister->nom }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Solde actuel</p>
                            <p class="text-lg font-semibold">{{ number_format($cashRegister->solde_actuel, 2, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('cash.transactions.store', ['cashRegister' => $cashRegister->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type de transaction</label>
                            <div class="flex space-x-4">
                                <div class="flex items-center">
                                    <input id="encaissement" name="type" type="radio" value="encaissement" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300" {{ old('type') == 'encaissement' ? 'checked' : '' }} required>
                                    <label for="encaissement" class="ml-2 block text-sm text-gray-700">Encaissement</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="decaissement" name="type" type="radio" value="decaissement" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300" {{ old('type') == 'decaissement' ? 'checked' : '' }}>
                                    <label for="decaissement" class="ml-2 block text-sm text-gray-700">Décaissement</label>
                                </div>
                            </div>
                            @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="montant" class="block text-sm font-medium text-gray-700 mb-1">Montant</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="montant" id="montant" step="0.01" min="0.01" value="{{ old('montant') }}" required
                                    class="focus:ring-red-500 focus:border-red-500 border py-2 block w-full pr-12 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">FCFA</span>
                                </div>
                            </div>
                            @error('montant')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="libelle" class="block text-sm font-medium text-gray-700 mb-1">Libellé</label>
                            <input type="text" name="libelle" id="libelle" value="{{ old('libelle') }}" required
                                class="focus:ring-red-500 border py-2 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            @error('libelle')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nature_operation" class="block text-sm font-medium text-gray-700 mb-1">Nature de l'opération</label>
                            <select name="nature_operation" id="nature_operation" required
                                class="focus:ring-red-500 focus:border-red-500 border py-2 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Sélectionner une nature</option>
                                @foreach($natures as $nature)
                                <option value="{{ $nature->nom }}" {{ old('nature_operation') == $nature->nom ? 'selected' : '' }}>{{ $nature->nom }}</option>
                                @endforeach
                            </select>
                            @error('nature_operation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mode_paiement" class="block text-sm font-medium text-gray-700 mb-1">Mode de paiement</label>
                            <select name="mode_paiement" id="mode_paiement" required
                                class="focus:ring-red-500 focus:border-red-500 border py-2 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="especes" {{ old('mode_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                                <option value="cheque" {{ old('mode_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                                <option value="mobile_money" {{ old('mode_paiement') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                <option value="virement" {{ old('mode_paiement') == 'virement' ? 'selected' : '' }}>Virement</option>
                            </select>
                            @error('mode_paiement')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="projet" class="block text-sm font-medium text-gray-700 mb-1">Projet (optionnel)</label>
                            <input type="text" name="projet" id="projet" value="{{ old('projet') }}"
                                class="focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            @error('projet')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="justificatif" class="block text-sm font-medium text-gray-700 mb-1">Justificatif (optionnel)</label>
                            <input type="file" name="justificatif" id="justificatif"
                                class="focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Formats acceptés: PDF, JPG, JPEG, PNG (max 2Mo)</p>
                            @error('justificatif')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <a href="{{ route('cash.registers.show', ['cashRegister' => $cashRegister->id]) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Annuler
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection