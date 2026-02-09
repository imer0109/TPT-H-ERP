

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 py-6">
    <div class="bg-white shadow-lg rounded-lg">
        <div class="border-b px-6 py-4">
            <h2 class="text-xl font-semibold">Modifier l'Alerte de Stock</h2>
        </div>

        <div class="p-6">
            <!-- Debug information -->
            <?php if(isset($stockAlert)): ?>
                <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded">
                    <p><strong>Stock Alert ID:</strong> <?php echo e($stockAlert->id); ?></p>
                    <p><strong>Stock Alert exists:</strong> <?php echo e($stockAlert->exists ? 'Yes' : 'No'); ?></p>
                </div>
            <?php else: ?>
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                    <p><strong>Error:</strong> Stock Alert not found or not passed to view</p>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('stock.alerts.update', isset($stockAlert) ? $stockAlert->id : 0)); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Produit -->
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Produit</label>
                    <select name="product_id" id="product_id" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                               sm:text-sm <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Sélectionnez un produit</option>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($product->id); ?>" <?php echo e(old('product_id', isset($stockAlert) ? $stockAlert->product_id : '') == $product->id ? 'selected' : ''); ?>>
                                <?php echo e($product->name); ?> (<?php echo e($product->reference); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['product_id'];
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

                <!-- Entrepôt -->
                <div>
                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Entrepôt</label>
                    <select name="warehouse_id" id="warehouse_id" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                               sm:text-sm <?php $__errorArgs = ['warehouse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Sélectionnez un entrepôt</option>
                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($warehouse->id); ?>" <?php echo e(old('warehouse_id', isset($stockAlert) ? $stockAlert->warehouse_id : '') == $warehouse->id ? 'selected' : ''); ?>>
                                <?php echo e($warehouse->name); ?>

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

                <!-- Seuil Minimum -->
                <div>
                    <label for="minimum_threshold" class="block text-sm font-medium text-gray-700">Seuil Minimum</label>
                    <input type="number" name="minimum_threshold" id="minimum_threshold" 
                        value="<?php echo e(old('minimum_threshold', isset($stockAlert) ? $stockAlert->seuil_minimum : '')); ?>"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                               sm:text-sm <?php $__errorArgs = ['minimum_threshold'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required min="0" step="0.01">
                    <?php $__errorArgs = ['minimum_threshold'];
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

                <!-- Seuil de Sécurité -->
                <div>
                    <label for="security_threshold" class="block text-sm font-medium text-gray-700">Seuil de Sécurité</label>
                    <input type="number" name="security_threshold" id="security_threshold" 
                        value="<?php echo e(old('security_threshold', isset($stockAlert) ? $stockAlert->seuil_securite : '')); ?>"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                               sm:text-sm <?php $__errorArgs = ['security_threshold'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required min="0" step="0.01">
                    <?php $__errorArgs = ['security_threshold'];
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

                <!-- Activer l'alerte -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" 
                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                           <?php echo e(old('is_active', isset($stockAlert) ? $stockAlert->alerte_active : false) ? 'checked' : ''); ?>>
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Activer l'alerte</label>
                </div>

                <!-- Notifications Email -->
                <div class="flex items-center">
                    <input type="checkbox" id="email_notifications" name="email_notifications" 
                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                           <?php echo e(old('email_notifications', isset($stockAlert) ? $stockAlert->email_notification : false) ? 'checked' : ''); ?>>
                    <label for="email_notifications" class="ml-2 block text-sm text-gray-700">Activer les notifications par email</label>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end gap-3">
                    <a href="<?php echo e(route('stock.alerts.index')); ?>" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Mettre à jour l'Alerte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\alerts\edit.blade.php ENDPATH**/ ?>