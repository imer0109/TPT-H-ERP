@extends('layouts.app')

@section('title', 'Modifier un Dépôt')

@section('content')
<div class="container mx-auto max-w-3xl px-4 py-8">
    <h1 class="text-2xl font-semibold mb-6">Modifier le Dépôt: {{ $warehouse->nom }}</h1>

    <form action="{{ route('stock.warehouses.update', $warehouse) }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code*</label>
            <input type="text" name="code" id="code" value="{{ old('code', $warehouse->code) }}" required
                   class="block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('code') border-red-500 @enderror">
            @error('code')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom*</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom', $warehouse->nom) }}" required
                   class="block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('nom') border-red-500 @enderror">
            @error('nom')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" id="description" rows="3"
                      class="block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('description') border-red-500 @enderror">{{ old('description', $warehouse->description) }}</textarea>
            @error('description')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type*</label>
            <select name="type" id="type" required
                    class="block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('type') border-red-500 @enderror">
                <option value="">Sélectionner un type</option>
                <option value="Principal" {{ old('type', $warehouse->type) == 'Principal' ? 'selected' : '' }}>Principal</option>
                <option value="Secondaire" {{ old('type', $warehouse->type) == 'Secondaire' ? 'selected' : '' }}>Secondaire</option>
                <option value="Transit" {{ old('type', $warehouse->type) == 'Transit' ? 'selected' : '' }}>Transit</option>
            </select>
            @error('type')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
            <input type="text" name="adresse" id="adresse" value="{{ old('adresse', $warehouse->adresse) }}"
                   class="block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('adresse') border-red-500 @enderror">
            @error('adresse')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="flex items-center space-x-2">
            <input type="checkbox" name="actif" id="actif" value="1" class="h-4 w-4 text-primary-600" {{ old('actif', $warehouse->actif) == '1' ? 'checked' : '' }}>
            <label for="actif" class="text-sm font-medium text-gray-700">Actif</label>
        </div>

        <div class="flex space-x-2 mt-4">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold px-4 py-2 rounded transition">Mettre à jour</button>
            <a href="{{ route('stock.warehouses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold px-4 py-2 rounded transition">Annuler</a>
        </div>
    </form>
</div>
@endsection
