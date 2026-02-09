@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="bg-white shadow rounded-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-primary-600 text-white px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17l6-6 4 4 6-6"></path>
                </svg>
                Nouveau Transfert de Stock
            </h2>
            <a href="{{ route('stock.transfers.index') }}" 
               class="bg-white text-primary-600 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-gray-100 transition">
                ← Retour
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('stock.transfers.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Dépôts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="warehouse_source_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Dépôt Source <span class="text-red-500">*</span>
                    </label>
                    <select name="warehouse_source_id" id="warehouse_source_id" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:border-primary-500 focus:ring focus:ring-primary-200
                               @error('warehouse_source_id') border-red-500 @enderror" required>
                        <option value="">-- Sélectionner un dépôt --</option>
                        @foreach($warehouses as $id => $name)
                            <option value="{{ $id }}" {{ old('warehouse_source_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('warehouse_source_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="warehouse_destination_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Dépôt Destination <span class="text-red-500">*</span>
                    </label>
                    <select name="warehouse_destination_id" id="warehouse_destination_id" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:border-primary-500 focus:ring focus:ring-primary-200
                               @error('warehouse_destination_id') border-red-500 @enderror" required>
                        <option value="">-- Sélectionner un dépôt --</option>
                        @foreach($warehouses as $id => $name)
                            <option value="{{ $id }}" {{ old('warehouse_destination_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('warehouse_destination_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Produit + Quantité + Unité -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="md:col-span-2">
                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Produit <span class="text-red-500">*</span>
                    </label>
                    <select name="product_id" id="product_id" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:border-primary-500 focus:ring focus:ring-primary-200
                               @error('product_id') border-red-500 @enderror" required>
                        <option value="">-- Sélectionner un produit --</option>
                        @foreach($products as $id => $name)
                            <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="quantite" class="block text-sm font-medium text-gray-700 mb-1">
                        Quantité <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" name="quantite" id="quantite" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:border-primary-500 focus:ring focus:ring-primary-200
                               @error('quantite') border-red-500 @enderror"
                        value="{{ old('quantite') }}" required>
                    @error('quantite')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="unite" class="block text-sm font-medium text-gray-700 mb-1">
                        Unité <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="unite" id="unite" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:border-primary-500 focus:ring focus:ring-primary-200
                               @error('unite') border-red-500 @enderror"
                        value="{{ old('unite') }}" required>
                    @error('unite')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Justificatif + Notes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="justificatif" class="block text-sm font-medium text-gray-700 mb-1">Justificatif</label>
                    <input type="file" name="justificatif" id="justificatif" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-600
                               focus:border-primary-500 focus:ring focus:ring-primary-200
                               @error('justificatif') border-red-500 @enderror">
                    @error('justificatif')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:border-primary-500 focus:ring focus:ring-primary-200
                               @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('stock.transfers.index') }}" 
                   class="px-5 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-5 py-2 rounded-lg bg-primary-600 text-white hover:bg-primary-700 transition">
                    Créer le transfert
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
