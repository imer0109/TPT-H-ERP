@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <div class="bg-white shadow-lg rounded-lg">
        <div class="border-b px-6 py-4">
            <h2 class="text-xl font-semibold">Modifier l'Alerte de Stock</h2>
        </div>

        <div class="p-6">
            <!-- Debug information -->
            @if(isset($stockAlert))
                <div class="mb-4 p-3 bg-primary-100 text-primary-800 rounded">
                    <p><strong>Stock Alert ID:</strong> {{ $stockAlert->id }}</p>
                    <p><strong>Stock Alert exists:</strong> {{ $stockAlert->exists ? 'Yes' : 'No' }}</p>
                </div>
            @else
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                    <p><strong>Error:</strong> Stock Alert not found or not passed to view</p>
                </div>
            @endif

            <form method="POST" action="{{ route('stock.alerts.update', isset($stockAlert) ? $stockAlert->id : 0) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Produit -->
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Produit</label>
                    <select name="product_id" id="product_id" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 
                               sm:text-sm @error('product_id') border-red-500 @enderror" required>
                        <option value="">Sélectionnez un produit</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id', isset($stockAlert) ? $stockAlert->product_id : '') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->reference }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Entrepôt -->
                <div>
                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Entrepôt</label>
                    <select name="warehouse_id" id="warehouse_id" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 
                               sm:text-sm @error('warehouse_id') border-red-500 @enderror" required>
                        <option value="">Sélectionnez un entrepôt</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id', isset($stockAlert) ? $stockAlert->warehouse_id : '') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Seuil Minimum -->
                <div>
                    <label for="minimum_threshold" class="block text-sm font-medium text-gray-700">Seuil Minimum</label>
                    <input type="number" name="minimum_threshold" id="minimum_threshold" 
                        value="{{ old('minimum_threshold', isset($stockAlert) ? $stockAlert->seuil_minimum : '') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 
                               sm:text-sm @error('minimum_threshold') border-red-500 @enderror" required min="0" step="0.01">
                    @error('minimum_threshold')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Seuil de Sécurité -->
                <div>
                    <label for="security_threshold" class="block text-sm font-medium text-gray-700">Seuil de Sécurité</label>
                    <input type="number" name="security_threshold" id="security_threshold" 
                        value="{{ old('security_threshold', isset($stockAlert) ? $stockAlert->seuil_securite : '') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 
                               sm:text-sm @error('security_threshold') border-red-500 @enderror" required min="0" step="0.01">
                    @error('security_threshold')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Activer l'alerte -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" 
                           class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                           {{ old('is_active', isset($stockAlert) ? $stockAlert->alerte_active : false) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Activer l'alerte</label>
                </div>

                <!-- Notifications Email -->
                <div class="flex items-center">
                    <input type="checkbox" id="email_notifications" name="email_notifications" 
                           class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                           {{ old('email_notifications', isset($stockAlert) ? $stockAlert->email_notification : false) ? 'checked' : '' }}>
                    <label for="email_notifications" class="ml-2 block text-sm text-gray-700">Activer les notifications par email</label>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('stock.alerts.index') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                        Mettre à jour l'Alerte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const minInput = document.getElementById("minimum_threshold");
    const secInput = document.getElementById("security_threshold");

    minInput.addEventListener("input", () => {
        if (parseFloat(minInput.value) > parseFloat(secInput.value)) {
            secInput.value = minInput.value;
        }
    });

    secInput.addEventListener("input", () => {
        if (parseFloat(secInput.value) < parseFloat(minInput.value)) {
            minInput.value = secInput.value;
        }
    });
});
</script>
@endpush
@endsection