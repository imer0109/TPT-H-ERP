

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-6 py-8">
    <div class="bg-white shadow rounded-2xl p-6">
        <h2 class="text-xl font-semibold mb-6">Créer une Nouvelle Alerte de Stock</h2>

        <form method="POST" action="<?php echo e(route('stock.alerts.store')); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>

            <!-- Produit -->
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Produit</label>
                <select name="product_id" id="product_id" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:border-blue-500 focus:ring focus:ring-blue-200
                               <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Sélectionnez un produit</option>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($id); ?>" <?php echo e(old('product_id') == $id ? 'selected' : ''); ?>>
                            <?php echo e($name); ?>

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
                <label for="warehouse_id" class="block text-sm font-medium text-gray-700 mb-1">Entrepôt</label>
                <select name="warehouse_id" id="warehouse_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:border-blue-500 focus:ring focus:ring-blue-200
                               <?php $__errorArgs = ['warehouse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Sélectionnez un entrepôt</option>
                    <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                        <option value="<?php echo e($id); ?>" <?php echo e(old('warehouse_id') == $id ? 'selected' : ''); ?>>
                            <?php echo e($nom); ?>

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
                <label for="minimum_threshold" class="block text-sm font-medium text-gray-700 mb-1">Seuil Minimum</label>
                <input type="number" name="minimum_threshold" id="minimum_threshold"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2
                              focus:border-blue-500 focus:ring focus:ring-blue-200
                              <?php $__errorArgs = ['minimum_threshold'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('minimum_threshold')); ?>" required min="0" step="0.01">
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
                <label for="security_threshold" class="block text-sm font-medium text-gray-700 mb-1">Seuil de Sécurité</label>
                <input type="number" name="security_threshold" id="security_threshold"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2
                              focus:border-blue-500 focus:ring focus:ring-blue-200
                              <?php $__errorArgs = ['security_threshold'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('security_threshold')); ?>" required min="0" step="0.01">
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

            <!-- Switch Activer Alerte -->
            <div class="flex items-center">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" id="is_active" name="is_active" 
                           <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                    <div class="w-11 h-6 bg-gray-300 peer-checked:bg-blue-600 rounded-full relative transition">
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
                           <?php echo e(old('email_notifications', true) ? 'checked' : ''); ?>>
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
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Créer l'Alerte
                </button>
                <a href="<?php echo e(route('stock.alerts.index')); ?>" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\alerts\create.blade.php ENDPATH**/ ?>