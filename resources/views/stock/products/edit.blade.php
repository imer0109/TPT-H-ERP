@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-3xl px-6 py-8">
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
             Modifier le produit
        </h1>

        <form method="post" action="{{ route('stock.products.update', $product) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nom -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input name="name" 
                       value="{{ old('name', $product->name) }}" 
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                       required />
                @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <!-- Quantité -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                <input type="number" min="0" 
                       name="quantite" 
                       value="{{ old('quantite', $product->quantite) }}" 
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                       required />
                @error('quantite')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <!-- Prix unitaire -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire</label>
                <input type="number" step="0.01" min="0" 
                       name="prix_unitaire" 
                       value="{{ old('prix_unitaire', $product->prix_unitaire) }}" 
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                       required />
                @error('prix_unitaire')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                          class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $product->description) }}</textarea>
                @error('description')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <!-- Boutons -->
            <div class="flex gap-3 pt-4">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md transition">
                    Enregistrer
                </button>
                <a href="{{ route('stock.products.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg shadow-md transition">
                     Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
