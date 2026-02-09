<div class="item-row border rounded-lg p-4 mb-4 bg-gray-50" data-index="<?php echo e($index); ?>">
    <div class="flex justify-between items-start mb-4">
        <h4 class="text-sm font-medium text-gray-700">Article <?php echo e((int)$index + 1); ?></h4>
        <?php if($index > 0): ?>
            <button type="button" class="remove-item text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Produit</label>
            <select name="items[<?php echo e($index); ?>][product_id]" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500 product-select">
                <option value="">Sélectionner un produit</option>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($product->id); ?>" 
                            data-name="<?php echo e($product->name); ?>"
                            <?php echo e(old("items.{$index}.product_id", $item ? $item->product_id : '') == $product->id ? 'selected' : ''); ?>>
                        <?php echo e($product->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Désignation <span class="text-red-500">*</span></label>
            <input type="text" name="items[<?php echo e($index); ?>][designation]" required
                   value="<?php echo e(old("items.{$index}.designation", $item ? $item->designation : '')); ?>"
                   placeholder="Description de l'article"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Quantité <span class="text-red-500">*</span></label>
            <input type="number" name="items[<?php echo e($index); ?>][quantite]" required min="1" step="1"
                   value="<?php echo e(old("items.{$index}.quantite", $item ? $item->quantite : '')); ?>"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Unité <span class="text-red-500">*</span></label>
            <select name="items[<?php echo e($index); ?>][unite]" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                <option value="">Unité</option>
                <option value="pièce" <?php echo e(old("items.{$index}.unite", $item ? $item->unite : '') == 'pièce' ? 'selected' : ''); ?>>Pièce</option>
                <option value="kg" <?php echo e(old("items.{$index}.unite", $item ? $item->unite : '') == 'kg' ? 'selected' : ''); ?>>Kg</option>
                <option value="litre" <?php echo e(old("items.{$index}.unite", $item ? $item->unite : '') == 'litre' ? 'selected' : ''); ?>>Litre</option>
                <option value="mètre" <?php echo e(old("items.{$index}.unite", $item ? $item->unite : '') == 'mètre' ? 'selected' : ''); ?>>Mètre</option>
                <option value="pack" <?php echo e(old("items.{$index}.unite", $item ? $item->unite : '') == 'pack' ? 'selected' : ''); ?>>Pack</option>
                <option value="carton" <?php echo e(old("items.{$index}.unite", $item ? $item->unite : '') == 'carton' ? 'selected' : ''); ?>>Carton</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire <span class="text-red-500">*</span></label>
            <input type="number" name="items[<?php echo e($index); ?>][prix_unitaire]" required min="0" step="0.01"
                   value="<?php echo e(old("items.{$index}.prix_unitaire", $item ? ($item->prix_unitaire_estime ?? $item->prix_unitaire ?? '') : '')); ?>"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
        </div>
    </div>

    <div class="mt-3 text-right">
        <span class="text-sm text-gray-600">Total: </span>
        <span class="font-medium item-total">0</span>
    </div>
</div>

<script>
// Auto-fill designation when product is selected
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('product-select')) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        if (selectedOption.value) {
            const designationInput = e.target.closest('.item-row').querySelector('[name*="[designation]"]');
            if (!designationInput.value) {
                designationInput.value = selectedOption.dataset.name;
            }
        }
    }
});

// Calculate item total
document.addEventListener('input', function(e) {
    if (e.target.matches('[name*="[quantite]"], [name*="[prix_unitaire]"]')) {
        const row = e.target.closest('.item-row');
        const quantite = parseFloat(row.querySelector('[name*="[quantite]"]').value) || 0;
        const prix = parseFloat(row.querySelector('[name*="[prix_unitaire]"]').value) || 0;
        const total = quantite * prix;
        row.querySelector('.item-total').textContent = new Intl.NumberFormat('fr-FR').format(total);
    }
});
</script><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\purchases\orders\_item_form.blade.php ENDPATH**/ ?>