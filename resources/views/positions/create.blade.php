@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Créer un Nouveau Poste</h1>
        <p class="mt-1 text-sm text-gray-500">Ajouter un nouveau poste dans l'organisation</p>
    </div>

    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('hr.positions.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-800">
            <i class="mdi mdi-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>

    <!-- Create Form -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Informations du Poste</h3>
        </div>
        <form action="{{ route('hr.positions.store') }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Title -->
                <div class="sm:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700">Titre du Poste <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('title') border-red-300 @enderror"
                           value="{{ old('title') }}" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700">Département</label>
                    <select id="department_id" name="department_id" 
                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="">Sélectionner un département</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Parent Position -->
                <div>
                    <label for="parent_position_id" class="block text-sm font-medium text-gray-700">Poste Hiérarchique Supérieur</label>
                    <select id="parent_position_id" name="parent_position_id" 
                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="">Aucun (Poste racine)</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ old('parent_position_id') == $pos->id ? 'selected' : '' }}>
                                {{ $pos->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_position_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Management -->
                <div class="sm:col-span-2">
                    <div class="flex items-center">
                        <input id="is_management" name="is_management" type="checkbox" 
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded" 
                               {{ old('is_management') ? 'checked' : '' }}>
                        <label for="is_management" class="ml-2 block text-sm text-gray-900">
                            Poste de management
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Cochez cette case si ce poste fait partie de la direction ou de l'encadrement.</p>
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="4" 
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end">
                <a href="{{ route('hr.positions.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Annuler
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Créer le Poste
                </button>
            </div>
        </form>
    </div>
</div>
@endsection