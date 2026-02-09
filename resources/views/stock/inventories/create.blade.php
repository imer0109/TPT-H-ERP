@extends('layouts.app')

@section('title', 'Créer un Inventaire')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="bg-white shadow-md rounded-lg">
        <!-- Header -->
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 class="text-lg font-semibold">Créer un Nouvel Inventaire</h3>
            <a href="{{ route('stock.inventories.index') }}" 
               class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>

        <form action="{{ route('stock.inventories.store') }}" method="POST">
            @csrf
            <div class="p-6">
                <!-- Alert info -->
                <div class="mb-6 p-4 border border-primary-200 bg-primary-50 rounded-md">
                    <h5 class="flex items-center text-primary-700 font-semibold mb-2">
                        <i class="fas fa-info-circle mr-2"></i> Information
                    </h5>
                    <p class="text-sm text-gray-600">La création d'un inventaire va générer une liste de tous les produits actifs avec leur stock théorique dans le dépôt sélectionné.</p>
                    <p class="text-sm text-gray-600 mt-1">Vous pourrez ensuite saisir les quantités réelles constatées lors de l'inventaire physique.</p>
                </div>

                <!-- Form fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dépôt -->
                    <div>
                        <label for="warehouse_id" class="block text-sm font-medium text-gray-700">
                            Dépôt <span class="text-red-500">*</span>
                        </label>
                        <select name="warehouse_id" id="warehouse_id" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                       focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm
                                       @error('warehouse_id') border-red-500 @enderror" required>
                            <option value="">Sélectionner un dépôt</option>
                            @foreach($warehouses as $id => $name)
                                <option value="{{ $id }}" {{ old('warehouse_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">
                            Date de l'inventaire <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" 
                               value="{{ old('date', date('Y-m-d')) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                      focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm
                                      @error('date') border-red-500 @enderror" required>
                        @error('date')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                     focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm
                                     @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t px-6 py-4 flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-md shadow-sm 
                               hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <i class="fas fa-save mr-2"></i> Créer l'Inventaire
                </button>
            </div>
        </form>
    </div>
</div>
@endsection