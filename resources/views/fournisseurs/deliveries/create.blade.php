@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Enregistrer une livraison fournisseur</h1>
        <a href="{{ route('fournisseurs.deliveries.index') }}" class="text-primary-600">Retour</a>
    </div>

    <form action="{{ route('fournisseurs.deliveries.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Commande associée</label>
                <select name="supplier_order_id" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Aucune commande</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ old('supplier_order_id') == $order->id ? 'selected' : '' }}>
                            {{ $order->numero_commande }} - {{ $order->fournisseur->raison_sociale }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fournisseur *</label>
                <select name="fournisseur_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Sélectionner un fournisseur</option>
                    @foreach($fournisseurs as $id => $name)
                        <option value="{{ $id }}" {{ old('fournisseur_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('fournisseur_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dépôt de réception *</label>
                <select name="warehouse_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Sélectionner un dépôt</option>
                    @foreach($warehouses as $id => $name)
                        <option value="{{ $id }}" {{ old('warehouse_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('warehouse_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Numéro BL *</label>
                <input type="text" name="numero_bl" value="{{ old('numero_bl') }}" required 
                       class="w-full border border-gray-300 rounded px-3 py-2">
                @error('numero_bl') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de réception *</label>
                <input type="date" name="date_reception" value="{{ old('date_reception', date('Y-m-d')) }}" required 
                       class="w-full border border-gray-300 rounded px-3 py-2">
                @error('date_reception') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                <select name="statut" required class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="received" {{ old('statut') == 'received' ? 'selected' : '' }}>Livré totalement</option>
                    <option value="partial" {{ old('statut') == 'partial' ? 'selected' : '' }}>Livré partiellement</option>
                    <option value="returned" {{ old('statut') == 'returned' ? 'selected' : '' }}>Retourné</option>
                </select>
                @error('statut') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded px-3 py-2">{{ old('notes') }}</textarea>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-medium mb-4">Articles livrés</h3>
            <div id="delivery-items">
                <div class="delivery-item border border-gray-200 p-4 rounded mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Produit *</label>
                            <select name="items[0][product_id]" required class="w-full border border-gray-300 rounded px-3 py-2">
                                <option value="">Sélectionner un produit</option>
                                @foreach($products as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantité livrée *</label>
                            <input type="number" name="items[0][quantite_livree]" min="1" required 
                                   class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantité commandée</label>
                            <input type="number" name="items[0][quantite_commandee]" min="0" 
                                   class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div class="flex items-end">
                            <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" onclick="addItem()" class="text-primary-600 hover:text-primary-800">
                + Ajouter un article
            </button>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded hover:bg-primary-700">
                Enregistrer la livraison
            </button>
        </div>
    </form>
</div>

<script>
let itemIndex = 1;

function addItem() {
    const container = document.getElementById('delivery-items');
    const newItem = document.createElement('div');
    newItem.className = 'delivery-item border border-gray-200 p-4 rounded mb-4';
    newItem.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Produit *</label>
                <select name="items[${itemIndex}][product_id]" required class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Sélectionner un produit</option>
                    @foreach($products as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantité livrée *</label>
                <input type="number" name="items[${itemIndex}][quantite_livree]" min="1" required 
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantité commandée</label>
                <input type="number" name="items[${itemIndex}][quantite_commandee]" min="0" 
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div class="flex items-end">
                <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800">
                    Supprimer
                </button>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    itemIndex++;
}

function removeItem(button) {
    button.closest('.delivery-item').remove();
}
</script>
@endsection


