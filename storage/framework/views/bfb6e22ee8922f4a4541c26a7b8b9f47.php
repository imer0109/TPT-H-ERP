<?php $__env->startSection('title', 'Modifier un Dépôt'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto max-w-3xl px-4 py-8">
    <h1 class="text-2xl font-semibold mb-6">Modifier le Dépôt: <?php echo e($warehouse->nom); ?></h1>

    <form action="<?php echo e(route('stock.warehouses.update', $warehouse)); ?>" method="POST" class="bg-white shadow rounded-lg p-6 space-y-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div>
            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code*</label>
            <input type="text" name="code" id="code" value="<?php echo e(old('code', $warehouse->code)); ?>" required
                   class="block w-full border border-gray-300 rounded-md shadow-sm p-2 <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom*</label>
            <input type="text" name="nom" id="nom" value="<?php echo e(old('nom', $warehouse->nom)); ?>" required
                   class="block w-full border border-gray-300 rounded-md shadow-sm p-2 <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" id="description" rows="3"
                      class="block w-full border border-gray-300 rounded-md shadow-sm p-2 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description', $warehouse->description)); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type*</label>
            <select name="type" id="type" required
                    class="block w-full border border-gray-300 rounded-md shadow-sm p-2 <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="">Sélectionner un type</option>
                <option value="Principal" <?php echo e(old('type', $warehouse->type) == 'Principal' ? 'selected' : ''); ?>>Principal</option>
                <option value="Secondaire" <?php echo e(old('type', $warehouse->type) == 'Secondaire' ? 'selected' : ''); ?>>Secondaire</option>
                <option value="Transit" <?php echo e(old('type', $warehouse->type) == 'Transit' ? 'selected' : ''); ?>>Transit</option>
            </select>
            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
            <input type="text" name="adresse" id="adresse" value="<?php echo e(old('adresse', $warehouse->adresse)); ?>"
                   class="block w-full border border-gray-300 rounded-md shadow-sm p-2 <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="flex items-center space-x-2">
            <input type="checkbox" name="actif" id="actif" value="1" class="h-4 w-4 text-blue-600" <?php echo e(old('actif', $warehouse->actif) == '1' ? 'checked' : ''); ?>>
            <label for="actif" class="text-sm font-medium text-gray-700">Actif</label>
        </div>

        <div class="flex space-x-2 mt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded transition">Mettre à jour</button>
            <a href="<?php echo e(route('stock.warehouses.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold px-4 py-2 rounded transition">Annuler</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\warehouses\edit.blade.php ENDPATH**/ ?>