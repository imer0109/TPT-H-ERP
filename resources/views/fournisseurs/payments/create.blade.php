@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Nouveau paiement fournisseur</h1>
        <a href="{{ route('fournisseurs.payments.index') }}" class="text-primary-600">Retour</a>
    </div>

    <form method="post" action="{{ route('fournisseurs.payments.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fournisseur *</label>
                <select name="fournisseur_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">Sélectionner un fournisseur</option>
                    @foreach($fournisseurs as $id => $name)
                        <option value="{{ $id }}" {{ old('fournisseur_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('fournisseur_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Facture associée</label>
                <select name="supplier_invoice_id" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Aucune facture</option>
                    @foreach($invoices as $id => $description)
                        <option value="{{ $id }}" {{ old('supplier_invoice_id') == $id ? 'selected' : '' }}>
                            {{ $description }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_invoice_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de paiement *</label>
                <input type="date" name="date_paiement" value="{{ old('date_paiement', date('Y-m-d')) }}" 
                       class="w-full border border-gray-300 rounded px-3 py-2" required>
                @error('date_paiement') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mode de paiement *</label>
                <select name="mode_paiement" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="virement" {{ old('mode_paiement') == 'virement' ? 'selected' : '' }}>Virement</option>
                    <option value="cheque" {{ old('mode_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                    <option value="especes" {{ old('mode_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                    <option value="carte" {{ old('mode_paiement') == 'carte' ? 'selected' : '' }}>Carte bancaire</option>
                    <option value="autre" {{ old('mode_paiement') == 'autre' ? 'selected' : '' }}>Autre</option>
                </select>
                @error('mode_paiement') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Montant *</label>
                <input type="number" step="0.01" min="0.01" name="montant" value="{{ old('montant') }}" 
                       class="w-full border border-gray-300 rounded px-3 py-2" required>
                @error('montant') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Devise</label>
                <input type="text" name="devise" value="{{ old('devise', 'XAF') }}" maxlength="3"
                       class="w-full border border-gray-300 rounded px-3 py-2">
                @error('devise') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Référence paiement</label>
                <input type="text" name="reference_paiement" value="{{ old('reference_paiement') }}" 
                       placeholder="N° chèque, référence virement..."
                       class="w-full border border-gray-300 rounded px-3 py-2">
                @error('reference_paiement') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Justificatif</label>
                <input type="file" name="justificatif" accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full border border-gray-300 rounded px-3 py-2">
                <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (max 2MB)</p>
                @error('justificatif') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded px-3 py-2">{{ old('notes') }}</textarea>
            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded hover:bg-primary-700">
                Enregistrer le paiement
            </button>
        </div>
    </form>
</div>
@endsection



