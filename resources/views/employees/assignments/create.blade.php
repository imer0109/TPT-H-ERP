@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nouvelle Affectation</h1>
            <p class="mt-1 text-sm text-gray-500">Créer une nouvelle affectation pour {{ $employee->full_name }}</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('hr.employees.show', $employee) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Retour au profil
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Détails de l'affectation</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Veuillez remplir les informations ci-dessous.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('hr.employees.assignments.store', $employee) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Company -->
                    <div class="sm:col-span-3">
                        <label for="company_id" class="block text-sm font-medium text-gray-700">Société</label>
                        <select id="company_id" name="company_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Sélectionner une société</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->raison_sociale }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Agency -->
                    <div class="sm:col-span-3">
                        <label for="agency_id" class="block text-sm font-medium text-gray-700">Agence</label>
                        <select id="agency_id" name="agency_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Sélectionner une agence</option>
                            @foreach($agencies as $agency)
                                <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>
                                    {{ $agency->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('agency_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Warehouse -->
                    <div class="sm:col-span-3">
                        <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Dépôt</label>
                        <select id="warehouse_id" name="warehouse_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Sélectionner un dépôt</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div class="sm:col-span-3">
                        <label for="position_id" class="block text-sm font-medium text-gray-700">Poste</label>
                        <select id="position_id" name="position_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Sélectionner un poste</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                    {{ $position->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('position_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div class="sm:col-span-3">
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div class="sm:col-span-3">
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Primary Assignment -->
                    <div class="sm:col-span-6">
                        <div class="flex items-center">
                            <input id="is_primary" name="is_primary" type="checkbox" {{ old('is_primary') ? 'checked' : '' }} class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="is_primary" class="ml-2 block text-sm text-gray-900">Affectation principale</label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Cochez cette case si cette affectation est l'affectation principale de l'employé.</p>
                        @error('is_primary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end">
                    <a href="{{ route('hr.employees.show', $employee) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                        Annuler
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none">
                        Créer l'affectation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection