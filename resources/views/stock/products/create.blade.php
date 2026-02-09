@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-3xl px-6 py-10">
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
             Nouveau produit
        </h1>

        <form method="post" action="{{ route('stock.products.store') }}" class="space-y-6">
            @csrf

            <!-- Nom -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                <input name="name" 
                    value="{{ old('name', $product->name ?? '') }}" 
                    class="w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2 shadow-sm"
                    placeholder="Ex: Ordinateur portable"
                    required />
                @error('name')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Quantité -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantité</label>
                <input type="number" min="0" name="quantite" 
                    value="{{ old('quantite') }}" 
                    class="w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2 shadow-sm"
                    placeholder="Ex: 50"
                    required />
                @error('quantite')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Prix unitaire -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prix unitaire</label>
                <input type="number" step="0.01" min="0" name="prix_unitaire" 
                    value="{{ old('prix_unitaire') }}" 
                    class="w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2 shadow-sm"
                    placeholder="Ex: 1500.00"
                    required />
                @error('prix_unitaire')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4"
                    class="w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2 shadow-sm resize-none"
                    placeholder="Ex: Produit de haute qualité avec garantie">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Boutons -->
            <div class="flex gap-3">
                <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md transition">
                     Enregistrer
                </button>
                <a href="{{ route('stock.products.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2 rounded-lg shadow-md transition">
                   Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
