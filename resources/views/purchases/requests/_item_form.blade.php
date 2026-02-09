<div class="grid grid-cols-1 md:grid-cols-6 gap-4">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Désignation <span class="text-red-500">*</span></label>
        <input type="text" name="items[{{ $index }}][designation]" required 
               value="{{ $item['designation'] ?? '' }}"
               placeholder="Nom de l'article ou service"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
        @error("items.{$index}.designation")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Produit (optionnel)</label>
        <select name="items[{{ $index }}][product_id]" 
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
            <option value="">Sélectionner un produit</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" 
                        {{ ($item['product_id'] ?? '') == $product->id ? 'selected' : '' }}>
                    {{ $product->libelle }}
                </option>
            @endforeach
        </select>
        @error("items.{$index}.product_id")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Quantité <span class="text-red-500">*</span></label>
        <input type="number" name="items[{{ $index }}][quantite]" required min="1" 
               value="{{ $item['quantite'] ?? 1 }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
        @error("items.{$index}.quantite")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Unité <span class="text-red-500">*</span></label>
        <select name="items[{{ $index }}][unite]" required 
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
            <option value="unité" {{ ($item['unite'] ?? '') == 'unité' ? 'selected' : '' }}>Unité</option>
            <option value="kg" {{ ($item['unite'] ?? '') == 'kg' ? 'selected' : '' }}>Kg</option>
            <option value="litre" {{ ($item['unite'] ?? '') == 'litre' ? 'selected' : '' }}>Litre</option>
            <option value="mètre" {{ ($item['unite'] ?? '') == 'mètre' ? 'selected' : '' }}>Mètre</option>
            <option value="carton" {{ ($item['unite'] ?? '') == 'carton' ? 'selected' : '' }}>Carton</option>
            <option value="boîte" {{ ($item['unite'] ?? '') == 'boîte' ? 'selected' : '' }}>Boîte</option>
            <option value="paquet" {{ ($item['unite'] ?? '') == 'paquet' ? 'selected' : '' }}>Paquet</option>
            <option value="jour" {{ ($item['unite'] ?? '') == 'jour' ? 'selected' : '' }}>Jour</option>
            <option value="heure" {{ ($item['unite'] ?? '') == 'heure' ? 'selected' : '' }}>Heure</option>
            <option value="mission" {{ ($item['unite'] ?? '') == 'mission' ? 'selected' : '' }}>Mission</option>
        </select>
        @error("items.{$index}.unite")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire estimé <span class="text-red-500">*</span></label>
        <input type="number" name="items[{{ $index }}][prix_unitaire_estime]" required min="0" step="0.01" 
               value="{{ $item['prix_unitaire_estime'] ?? '' }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
        @error("items.{$index}.prix_unitaire_estime")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="items[{{ $index }}][description]" rows="2"
                  placeholder="Description détaillée de l'article"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">{{ $item['description'] ?? '' }}</textarea>
        @error("items.{$index}.description")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Fournisseur suggéré</label>
        <select name="items[{{ $index }}][fournisseur_suggere_id]" 
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
            <option value="">Aucun fournisseur suggéré</option>
            @foreach($fournisseurs as $fournisseur)
                <option value="{{ $fournisseur->id }}" 
                        {{ ($item['fournisseur_suggere_id'] ?? '') == $fournisseur->id ? 'selected' : '' }}>
                    {{ $fournisseur->nom }}
                </option>
            @endforeach
        </select>
        @error("items.{$index}.fournisseur_suggere_id")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
        <textarea name="items[{{ $index }}][notes]" rows="2"
                  placeholder="Notes spécifiques à cet article"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">{{ $item['notes'] ?? '' }}</textarea>
        @error("items.{$index}.notes")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex justify-between items-center mt-4 pt-4 border-t">
    <div class="text-sm text-gray-600">
        Total estimé: <span class="font-medium item-total">0 FCFA</span>
    </div>
    <button type="button" class="remove-item text-red-600 hover:text-red-800 transition">
        <i class="fas fa-trash mr-1"></i>Supprimer cet article
    </button>
</div>