@extends('layouts.app')

@section('title', 'Créer un Dépôt')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Créer un Nouveau Dépôt</h1>

        <form action="{{ route('stock.warehouses.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4">

                    {{-- Code --}}
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">Code*</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" required
                            class="mt-1 focus:ring-red-500 border py-2 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('code') border-red-500 @enderror">
                        @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nom --}}
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom*</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                            class="mt-1 focus:ring-red-500 border py-2 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('nom') border-red-500 @enderror">
                        @error('nom')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 focus:ring-red-500 focus:border-red-500 block border w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Type*</label>
                        <select name="type" id="type" required
                            class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('type') border-red-500 @enderror">
                            <option value="">Sélectionner un type</option>
                            <option value="Principal" {{ old('type') == 'Principal' ? 'selected' : '' }}>Principal</option>
                            <option value="Secondaire" {{ old('type') == 'Secondaire' ? 'selected' : '' }}>Secondaire</option>
                            <option value="Transit" {{ old('type') == 'Transit' ? 'selected' : '' }}>Transit</option>
                        </select>
                        @error('type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Adresse --}}
                    <div>
                        <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}"
                            class="mt-1 focus:ring-red-500 border py-2 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('adresse') border-red-500 @enderror">
                        @error('adresse')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actif --}}
                    <div class="flex items-center">
                        <input type="checkbox" name="actif" id="actif" value="1" 
                               {{ old('actif', '1') == '1' ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="actif" class="ml-2 block text-sm text-gray-700">Actif</label>
                    </div>
                </div>
            </div>

            {{-- Boutons --}}
            <div class="flex justify-end">
                <a href="{{ route('stock.warehouses.index') }}"
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Annuler
                </a>
                <button type="submit"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
