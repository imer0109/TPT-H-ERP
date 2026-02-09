<?php $__env->startSection('title', 'Créer un Inventaire'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="bg-white shadow-md rounded-lg">
        <!-- Header -->
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 class="text-lg font-semibold">Créer un Nouvel Inventaire</h3>
            <a href="<?php echo e(route('stock.inventories.index')); ?>" 
               class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>

        <form action="<?php echo e(route('stock.inventories.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="p-6">
                <!-- Alert info -->
                <div class="mb-6 p-4 border border-blue-200 bg-blue-50 rounded-md">
                    <h5 class="flex items-center text-blue-700 font-semibold mb-2">
                        <i class="fas fa-info-circle mr-2"></i> Information
                    </h5>
                    <p class="text-sm text-gray-600">La création d'un inventaire va générer une liste de tous les produits actifs avec leur stock théorique dans le dépôt sélectionné.</p>
                    <p class="text-sm text-gray-600 mt-1">Vous pourrez ensuite saisir les quantités réelles constatées lors de l'inventaire physique.</p>
                </div>

                <!-- Form fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dépôt -->
                    <div>
                        <label for="warehouse_id" class="block text-sm font-medium text-gray-700">
                            Dépôt <span class="text-red-500">*</span>
                        </label>
                        <select name="warehouse_id" id="warehouse_id" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                                       <?php $__errorArgs = ['warehouse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
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
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-sm text-red-500"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">
                            Date de l'inventaire <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" 
                               value="<?php echo e(old('date', date('Y-m-d'))); ?>"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                                      <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-sm text-red-500"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                     focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                                     <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('notes')); ?></textarea>
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-sm text-red-500"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t px-6 py-4 flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm 
                               hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i> Créer l'Inventaire
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\inventories\create.blade.php ENDPATH**/ ?>