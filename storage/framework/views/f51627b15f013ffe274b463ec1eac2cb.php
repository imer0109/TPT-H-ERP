

<?php $__env->startSection('content'); ?>
<div class="container mx-auto max-w-3xl px-6 py-8">
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
             Modifier le produit
        </h1>

        <form method="post" action="<?php echo e(route('stock.products.update', $product)); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Nom -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input name="name" 
                       value="<?php echo e(old('name', $product->name)); ?>" 
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                       required />
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Quantité -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                <input type="number" min="0" 
                       name="quantite" 
                       value="<?php echo e(old('quantite', $product->quantite)); ?>" 
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                       required />
                <?php $__errorArgs = ['quantite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Prix unitaire -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire</label>
                <input type="number" step="0.01" min="0" 
                       name="prix_unitaire" 
                       value="<?php echo e(old('prix_unitaire', $product->prix_unitaire)); ?>" 
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                       required />
                <?php $__errorArgs = ['prix_unitaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                          class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?php echo e(old('description', $product->description)); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Boutons -->
            <div class="flex gap-3 pt-4">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md transition">
                    Enregistrer
                </button>
                <a href="<?php echo e(route('stock.products.index')); ?>" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg shadow-md transition">
                     Annuler
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\products\edit.blade.php ENDPATH**/ ?>