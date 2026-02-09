@extends('layouts.app')

@section('title', 'Créer un Mouvement de Stock')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">Créer un Mouvement de Stock</h3>
            <a href="{{ route('stock.movements.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('stock.movements.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="type" class="block mb-1 font-medium">Type de Mouvement*</label>
                    <select name="type" id="type" class="border rounded w-full p-2 @error('type') border-red-500 @enderror" required>
                        <option value="">Sélectionner un type</option>
                        <option value="entree" {{ old('type') == 'entree' ? 'selected' : '' }}>Entrée</option>
                        <option value="sortie" {{ old('type') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1"> {{ $message }} </p>
                    @enderror
                </div>

                <div>
                    <label for="source" class="block mb-1 font-medium">Source*</label>
                    <select name="source" id="source" class="border rounded w-full p-2 @error('source') border-red-500 @enderror" required>
                        <option value="">Sélectionner une source</option>
                        <option value="achat" {{ old('source') == 'achat' ? 'selected' : '' }}>Achat</option>
                        <option value="production" {{ old('source') == 'production' ? 'selected' : '' }}>Production</option>
                        <option value="don" {{ old('source') == 'don' ? 'selected' : '' }}>Don</option>
                        <option value="vente" {{ old('source') == 'vente' ? 'selected' : '' }}>Vente</option>
                        <option value="consommation" {{ old('source') == 'consommation' ? 'selected' : '' }}>Consommation</option>
                        <option value="perte" {{ old('source') == 'perte' ? 'selected' : '' }}>Perte</option>
                        <option value="transfert" {{ old('source') == 'transfert' ? 'selected' : '' }}>Transfert</option>
                    </select>
                    @error('source')
                        <p class="text-red-500 text-sm mt-1"> {{ $message }} </p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="warehouse_id" class="block mb-1 font-medium">Dépôt*</label>
                    <select name="warehouse_id" id="warehouse_id" class="border rounded w-full p-2 @error('warehouse_id') border-red-500 @enderror" required>
                        <option value="">Sélectionner un dépôt</option>
                        @foreach($warehouses as $id => $name)
                            <option value="{{ $id }}" {{ old('warehouse_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <p class="text-red-500 text-sm mt-1"> {{ $message }} </p>
                    @enderror
                </div>

                <div>
                    <label for="product_id" class="block mb-1 font-medium">Produit*</label>
                    <select name="product_id" id="product_id" class="border rounded w-full p-2 @error('product_id') border-red-500 @enderror" required>
                        <option value="">Sélectionner un produit</option>
                        @foreach($products as $id => $name)
                            <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-red-500 text-sm mt-1"> {{ $message }} </p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="quantite" class="block mb-1 font-medium">Quantité*</label>
                    <input type="number" name="quantite" id="quantite" value="{{ old('quantite') }}" min="1" step="1" required
                        class="border rounded w-full p-2 text-right @error('quantite') border-red-500 @enderror">
                    @error('quantite')
                        <p class="text-red-500 text-sm mt-1"> {{ $message }} </p>
                    @enderror
                </div>

                <div>
                    <label for="unite" class="block mb-1 font-medium">Unité*</label>
                    <select name="unite" id="unite" class="border rounded w-full p-2 @error('unite') border-red-500 @enderror" required>
                        <option value="">Sélectionner une unité</option>
                        <option value="unité" {{ old('unite') == 'unité' ? 'selected' : '' }}>Unité</option>
                        <option value="pièce" {{ old('unite') == 'pièce' ? 'selected' : '' }}>Pièce</option>
                        <option value="kg" {{ old('unite') == 'kg' ? 'selected' : '' }}>Kg</option>
                        <option value="litre" {{ old('unite') == 'litre' ? 'selected' : '' }}>Litre</option>
                        <option value="mètre" {{ old('unite') == 'mètre' ? 'selected' : '' }}>Mètre</option>
                        <option value="pack" {{ old('unite') == 'pack' ? 'selected' : '' }}>Pack</option>
                        <option value="carton" {{ old('unite') == 'carton' ? 'selected' : '' }}>Carton</option>
                    </select>
                    @error('unite')
                        <p class="text-red-500 text-sm mt-1"> {{ $message }} </p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="prix_unitaire" class="block mb-1 font-medium">Prix Unitaire*</label>
                    <input type="number" name="prix_unitaire" id="prix_unitaire" value="{{ old('prix_unitaire') }}" min="0" step="0.01" required
                        class="border rounded w-full p-2 text-right @error('prix_unitaire') border-red-500 @enderror">
                    @error('prix_unitaire')
                        <p class="text-red-500 text-sm mt-1"> {{ $message }} </p>
                    @enderror
                </div>

                <div>
                    <label for="reference" class="block mb-1 font-medium">Référence</label>
                    <input type="text" name="reference" id="reference" value="{{ old('reference') }}" placeholder="Facultatif"
                        class="border rounded w-full p-2 @error('reference') border-red-500 @enderror">
                    @error('reference')
                        <p class="text-red-500 text-sm mt-1"> {{ $message }} </p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="motif" class="block mb-1 font-medium">Motif*</label>
                <input type="text" name="motif" id="motif" value="{{ old('motif') }}" placeholder="Motif du mouvement" required
                    class="border rounded w-full p-2 @error('motif') border-red-500 @enderror">
                @error('motif')
                    <p class="text-red-500 text-sm mt-1"> {{ $message }} </p>
                @enderror
            </div>

            <!-- Hidden input for montant_total -->
            <input type="hidden" name="montant_total" id="montant_total_hidden" value="{{ old('montant_total', '0.00') }}">

            <div class="mb-4">
                <label for="montant_total_display" class="block mb-1 font-medium">Montant Total</label>
                <input type="text" id="montant_total_display" readonly class="border rounded w-full p-2 text-right bg-gray-100">
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded">Enregistrer</button>
                <a href="{{ route('stock.movements.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantiteInput = document.getElementById('quantite');
        const prixInput = document.getElementById('prix_unitaire');
        const totalInput = document.getElementById('montant_total_display');
        const hiddenTotalInput = document.getElementById('montant_total_hidden');

        function toNumber(raw) {
            if (typeof raw !== 'string') raw = String(raw ?? '');
            // Supprime espaces insécables et normaux
            let s = raw.replace(/[\s\u00A0]/g, '');
            const hasComma = s.includes(',');
            const hasDot = s.includes('.');
            if (hasComma && hasDot) {
                // Cas "1.234,56" (FR): les points sont des milliers, la virgule est décimale
                s = s.replace(/\./g, '').replace(/,/g, '.');
            } else if (hasComma) {
                // Cas "1234,56": virgule décimale
                s = s.replace(/,/g, '.');
            }
            // Garde uniquement chiffres, un seul point décimal et signe
            s = s.replace(/[^0-9.\-]/g, '');
            const n = parseFloat(s);
            return Number.isFinite(n) ? n : 0;
        }

        function updateTotal() {
            const quantite = toNumber(quantiteInput.value);
            const prix = toNumber(prixInput.value);
            const total = quantite * prix;
            
            // Update both display and hidden input
            totalInput.value = total.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            hiddenTotalInput.value = total.toFixed(2);
        }

        ['input', 'change', 'blur'].forEach(evt => {
            quantiteInput.addEventListener(evt, updateTotal);
            prixInput.addEventListener(evt, updateTotal);
        });

        updateTotal(); // Initialisation au chargement
    });
</script>
@endsection