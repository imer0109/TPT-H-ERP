@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">
    <div class="bg-white shadow rounded-2xl p-6">
        <h2 class="text-xl font-semibold mb-6">Créer une Nouvelle Alerte de Stock</h2>

        <form method="POST" action="{{ route('stock.alerts.store') }}" class="space-y-6">
            @csrf

            <!-- Produit -->
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Produit</label>
                <select name="product_id" id="product_id" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:border-primary-500 focus:ring focus:ring-primary-200
                               @error('product_id') border-red-500 @enderror" required>
                    <option value="">Sélectionnez un produit</option>
                    @foreach($products as $id => $name)
                        <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Entrepôt -->
            <div>
                <label for="warehouse_id" class="block text-sm font-medium text-gray-700 mb-1">Entrepôt</label>
                <select name="warehouse_id" id="warehouse_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:border-primary-500 focus:ring focus:ring-primary-200
                               @error('warehouse_id') border-red-500 @enderror" required>
                    <option value="">Sélectionnez un entrepôt</option>
                    @foreach($warehouses as $id => $nom) 
                        <option value="{{ $id }}" {{ old('warehouse_id') == $id ? 'selected' : '' }}>
                            {{ $nom }}
                        </option>
                    @endforeach
                </select>
                @error('warehouse_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Seuil Minimum -->
            <div>
                <label for="minimum_threshold" class="block text-sm font-medium text-gray-700 mb-1">Seuil Minimum</label>
                <input type="number" name="minimum_threshold" id="minimum_threshold"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2
                              focus:border-primary-500 focus:ring focus:ring-primary-200
                              @error('minimum_threshold') border-red-500 @enderror"
                       value="{{ old('minimum_threshold') }}" required min="0" step="0.01">
                @error('minimum_threshold')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Seuil de Sécurité -->
            <div>
                <label for="security_threshold" class="block text-sm font-medium text-gray-700 mb-1">Seuil de Sécurité</label>
                <input type="number" name="security_threshold" id="security_threshold"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2
                              focus:border-primary-500 focus:ring focus:ring-primary-200
                              @error('security_threshold') border-red-500 @enderror"
                       value="{{ old('security_threshold') }}" required min="0" step="0.01">
                @error('security_threshold')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Switch Activer Alerte -->
            <div class="flex items-center">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" id="is_active" name="is_active" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-300 peer-checked:bg-primary-600 rounded-full relative transition">
                        <div class="absolute w-5 h-5 bg-white rounded-full shadow -left-0.5 top-0.5 
                                    peer-checked:translate-x-5 transform transition"></div>
                    </div>
                    <span class="ml-3 text-sm text-gray-700">Activer l'alerte</span>
                </label>
            </div>

            <!-- Switch Notifications Email -->
            <div class="flex items-center">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" id="email_notifications" name="email_notifications"
                           {{ old('email_notifications', true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-300 peer-checked:bg-green-600 rounded-full relative transition">
                        <div class="absolute w-5 h-5 bg-white rounded-full shadow -left-0.5 top-0.5 
                                    peer-checked:translate-x-5 transform transition"></div>
                    </div>
                    <span class="ml-3 text-sm text-gray-700">Activer les notifications par email</span>
                </label>
            </div>

            <!-- Boutons -->
            <div class="flex space-x-3 pt-4">
                <button type="submit" 
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    Créer l'Alerte
                </button>
                <a href="{{ route('stock.alerts.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#security_threshold').on('input', function() {
        const securityThreshold = parseFloat($(this).val()) || 0;
        const minimumThreshold = parseFloat($('#minimum_threshold').val()) || 0;
        
        if (minimumThreshold > securityThreshold) {
            $('#minimum_threshold').val(securityThreshold);
        }
    });

    $('#minimum_threshold').on('input', function() {
        const minimumThreshold = parseFloat($(this).val()) || 0;
        const securityThreshold = parseFloat($('#security_threshold').val()) || 0;
        
        if (minimumThreshold > securityThreshold) {
            $('#security_threshold').val(minimumThreshold);
        }
    });
});
</script>
@endpush
@endsection
