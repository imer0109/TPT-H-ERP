@extends('layouts.app')

@section('title', 'Ajouter un document - ' . $fournisseur->raison_sociale)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Ajouter un document pour {{ $fournisseur->raison_sociale }}
                    </h2>
                    <a href="{{ route('fournisseurs.documents.index', $fournisseur) }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Retour
                    </a>
                </div>

                <form action="{{ route('fournisseurs.documents.store', $fournisseur) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Type -->
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Type de document *
                            </label>
                            <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <option value="">Sélectionnez un type</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nom -->
                        <div class="mb-4">
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom du document *
                            </label>
                            <input type="text" name="nom" id="nom" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                   value="{{ old('nom') }}" required>
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date d'expiration -->
                        <div class="mb-4">
                            <label for="date_expiration" class="block text-sm font-medium text-gray-700 mb-2">
                                Date d'expiration
                            </label>
                            <input type="date" name="date_expiration" id="date_expiration" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                   value="{{ old('date_expiration') }}">
                            @error('date_expiration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document -->
                        <div class="mb-4">
                            <label for="document" class="block text-sm font-medium text-gray-700 mb-2">
                                Fichier *
                            </label>
                            <input type="file" name="document" id="document" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Formats acceptés: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG. Taille maximale: 10MB</p>
                            @error('document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4 md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <a href="{{ route('fournisseurs.documents.index', $fournisseur) }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                            Ajouter le document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection