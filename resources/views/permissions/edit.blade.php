@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Modifier la Permission</h1>

        <form action="{{ route('permissions.update', $permission) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4">
                    <div>
                        <label for="module" class="block text-sm font-medium text-gray-700">Module</label>
                        <input type="text" name="module" id="module" value="{{ old('module', $permission->module) }}" required
                            class="mt-1 focus:ring-red-500 border py-2 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('module')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom de la permission</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom', $permission->nom) }}" required
                            class="mt-1 focus:ring-red-500 border py-2 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('nom')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="action" class="block text-sm font-medium text-gray-700">Action</label>
                        <select name="action" id="action" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                            <option value="create" {{ old('action', $permission->action) === 'create' ? 'selected' : '' }}>Créer</option>
                            <option value="read" {{ old('action', $permission->action) === 'read' ? 'selected' : '' }}>Lire</option>
                            <option value="update" {{ old('action', $permission->action) === 'update' ? 'selected' : '' }}>Modifier</option>
                            <option value="delete" {{ old('action', $permission->action) === 'delete' ? 'selected' : '' }}>Supprimer</option>
                        </select>
                        @error('action')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('permissions.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Annuler
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection