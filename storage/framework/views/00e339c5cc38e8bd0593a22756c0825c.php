

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Enregistrer une livraison fournisseur</h1>
        <a href="<?php echo e(route('fournisseurs.deliveries.index')); ?>" class="text-blue-600">Retour</a>
    </div>

    <form action="<?php echo e(route('fournisseurs.deliveries.store')); ?>" method="POST" class="bg-white p-6 rounded shadow">
        <?php echo csrf_field(); ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Commande associée</label>
                <select name="supplier_order_id" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Aucune commande</option>
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($order->id); ?>" <?php echo e(old('supplier_order_id') == $order->id ? 'selected' : ''); ?>>
                            <?php echo e($order->numero_commande); ?> - <?php echo e($order->fournisseur->raison_sociale); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fournisseur *</label>
                <select name="fournisseur_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Sélectionner un fournisseur</option>
                    <?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($id); ?>" <?php echo e(old('fournisseur_id') == $id ? 'selected' : ''); ?>>
                            <?php echo e($name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['fournisseur_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dépôt de réception *</label>
                <select name="warehouse_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Sélectionner un dépôt</option>
                    <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($id); ?>" <?php echo e(old('warehouse_id') == $id ? 'selected' : ''); ?>>
                            <?php echo e($name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['warehouse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Numéro BL *</label>
                <input type="text" name="numero_bl" value="<?php echo e(old('numero_bl')); ?>" required 
                       class="w-full border border-gray-300 rounded px-3 py-2">
                <?php $__errorArgs = ['numero_bl'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de réception *</label>
                <input type="date" name="date_reception" value="<?php echo e(old('date_reception', date('Y-m-d'))); ?>" required 
                       class="w-full border border-gray-300 rounded px-3 py-2">
                <?php $__errorArgs = ['date_reception'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                <select name="statut" required class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="received" <?php echo e(old('statut') == 'received' ? 'selected' : ''); ?>>Livré totalement</option>
                    <option value="partial" <?php echo e(old('statut') == 'partial' ? 'selected' : ''); ?>>Livré partiellement</option>
                    <option value="returned" <?php echo e(old('statut') == 'returned' ? 'selected' : ''); ?>>Retourné</option>
                </select>
                <?php $__errorArgs = ['statut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded px-3 py-2"><?php echo e(old('notes')); ?></textarea>
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
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            <button type="button" onclick="addItem()" class="text-blue-600 hover:text-blue-800">
                + Ajouter un article
            </button>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
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
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\deliveries\create.blade.php ENDPATH**/ ?>