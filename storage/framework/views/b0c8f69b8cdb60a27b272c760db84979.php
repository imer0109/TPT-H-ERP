

<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Nouvelle livraison</h2>
                    <a href="<?php echo e(route('purchases.orders.show', $order)); ?>" 
                       class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Retour à la commande
                    </a>
                </div>

                <?php if(session('error')): ?>
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('purchases.deliveries.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="supplier_order_id" value="<?php echo e($order->id); ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="numero_bl" class="block text-sm font-medium text-gray-700">Numéro du bon de livraison</label>
                            <input type="text" name="numero_bl" id="numero_bl" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                   value="<?php echo e(old('numero_bl')); ?>" required>
                            <?php $__errorArgs = ['numero_bl'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="date_reception" class="block text-sm font-medium text-gray-700">Date de réception</label>
                            <input type="date" name="date_reception" id="date_reception" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                   value="<?php echo e(old('date_reception', now()->format('Y-m-d'))); ?>" required>
                            <?php $__errorArgs = ['date_reception'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Entrepôt de réception</label>
                            <select name="warehouse_id" id="warehouse_id" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                    required>
                                <option value="">Sélectionner un entrepôt</option>
                                <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($warehouse->id); ?>" <?php echo e(old('warehouse_id') == $warehouse->id ? 'selected' : ''); ?>>
                                        <?php echo e($warehouse->nom); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['warehouse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="condition_emballage" class="block text-sm font-medium text-gray-700">Condition de l'emballage</label>
                            <select name="condition_emballage" id="condition_emballage" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="">Sélectionner une condition</option>
                                <option value="bon" <?php echo e(old('condition_emballage') == 'bon' ? 'selected' : ''); ?>>Bon</option>
                                <option value="moyen" <?php echo e(old('condition_emballage') == 'moyen' ? 'selected' : ''); ?>>Moyen</option>
                                <option value="mauvais" <?php echo e(old('condition_emballage') == 'mauvais' ? 'selected' : ''); ?>>Mauvais</option>
                            </select>
                            <?php $__errorArgs = ['condition_emballage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"><?php echo e(old('notes')); ?></textarea>
                        <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Articles commandés</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Désignation</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité commandée</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité livrée</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Écart</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?php echo e($item->designation); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-sm text-gray-900"><?php echo e($item->quantite); ?></div>
                                                <input type="hidden" name="items[<?php echo e($index); ?>][order_item_id]" value="<?php echo e($item->id); ?>">
                                                <input type="hidden" name="items[<?php echo e($index); ?>][product_id]" value="<?php echo e($item->product_id); ?>">
                                                <input type="hidden" name="items[<?php echo e($index); ?>][quantite_commandee]" value="<?php echo e($item->quantite); ?>">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" name="items[<?php echo e($index); ?>][quantite_livree]" 
                                                       class="w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 text-center quantite-livree"
                                                       min="0" value="<?php echo e(old('items.' . $index . '.quantite_livree', $item->quantite)); ?>" required>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="ecart-display text-sm font-medium">0</span>
                                                <input type="hidden" name="items[<?php echo e($index); ?>][ecart]" class="ecart-input" value="0">
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="text" name="items[<?php echo e($index); ?>][notes]" 
                                                       class="w-full border border-gray-300 rounded-md shadow-sm py-1 px-2 text-sm"
                                                       value="<?php echo e(old('items.' . $index . '.notes')); ?>"
                                                       placeholder="Notes sur cet article">
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="<?php echo e(route('purchases.orders.show', $order)); ?>" 
                           class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Enregistrer la livraison
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate discrepancies when quantity delivered changes
    document.querySelectorAll('.quantite-livree').forEach(function(input) {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            const quantiteCommandee = parseInt(row.querySelector('[name$="[quantite_commandee]"]').value);
            const quantiteLivree = parseInt(this.value) || 0;
            const ecart = quantiteLivree - quantiteCommandee;
            
            row.querySelector('.ecart-display').textContent = ecart;
            row.querySelector('.ecart-input').value = ecart;
            
            // Color code the discrepancy
            const ecartDisplay = row.querySelector('.ecart-display');
            if (ecart > 0) {
                ecartDisplay.className = 'ecart-display text-sm font-medium text-green-600';
            } else if (ecart < 0) {
                ecartDisplay.className = 'ecart-display text-sm font-medium text-red-600';
            } else {
                ecartDisplay.className = 'ecart-display text-sm font-medium';
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\purchases\deliveries\create.blade.php ENDPATH**/ ?>