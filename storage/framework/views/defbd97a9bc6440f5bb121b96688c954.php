

<?php $__env->startSection('title', 'Modifier l\'Inventaire'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Modifier l'Inventaire #<?php echo e($inventory->reference); ?>

        </h1>

        <a href="<?php echo e(route('stock.inventories.show', $inventory)); ?>"
           class="mt-3 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
            ← Retour
        </a>
    </div>

    <form action="<?php echo e(route('stock.inventories.update', $inventory)); ?>" method="POST"
          class="bg-white shadow rounded-lg">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Info box -->
        <div class="p-6 border-b">
            <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-lg p-4">
                <h4 class="font-semibold mb-1">Information</h4>
                <p class="text-sm">
                    Veuillez saisir les quantités réelles constatées lors de l'inventaire physique.
                    Les différences seront automatiquement calculées.
                </p>
            </div>
        </div>

        <!-- Inventory info -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
            <div>
                <span class="font-semibold">Référence :</span> <?php echo e($inventory->reference); ?>

            </div>
            <div>
                <span class="font-semibold">Date :</span> <?php echo e($inventory->date->format('d/m/Y')); ?>

            </div>
            <div>
                <span class="font-semibold">Dépôt :</span> <?php echo e($inventory->warehouse->nom); ?>

            </div>
        </div>

        <!-- Notes -->
        <div class="px-6 pb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="3"
                class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300 <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('notes', $inventory->notes)); ?></textarea>
            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto px-6 pb-6">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-100">
                    <tr class="text-sm text-gray-700">
                        <th class="px-4 py-3 text-left">Produit</th>
                        <th class="px-4 py-3 text-left">Référence</th>
                        <th class="px-4 py-3 text-right">Stock Théorique</th>
                        <th class="px-4 py-3 text-right">Stock Réel</th>
                        <th class="px-4 py-3 text-right">Différence</th>
                        <th class="px-4 py-3 text-left">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y">

                    <?php $__currentLoopData = $inventory->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 text-sm">
                        <td class="px-4 py-2"><?php echo e($item->product->name); ?></td>
                        <td class="px-4 py-2"><?php echo e($item->product->reference); ?></td>

                        <td class="px-4 py-2 text-right">
                            <?php echo e(number_format($item->theoretical_quantity, 2, ',', ' ')); ?>

                        </td>

                        <td class="px-4 py-2">
                            <input type="number" step="0.01"
                                name="items[<?php echo e($item->id); ?>][actual_quantity]"
                                value="<?php echo e(old('items.'.$item->id.'.actual_quantity', $item->actual_quantity)); ?>"
                                class="w-full border rounded px-2 py-1 text-right focus:ring focus:ring-blue-300">
                        </td>

                        <td class="px-4 py-2 text-right">
                            <?php if($item->difference !== null): ?>
                                <span class="<?php echo e($item->difference < 0 ? 'text-red-600' : ($item->difference > 0 ? 'text-green-600' : '')); ?>">
                                    <?php echo e(number_format($item->difference, 2, ',', ' ')); ?>

                                </span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>

                        <td class="px-4 py-2">
                            <input type="text"
                                name="items[<?php echo e($item->id); ?>][notes]"
                                value="<?php echo e(old('items.'.$item->id.'.notes', $item->notes)); ?>"
                                class="w-full border rounded px-2 py-1">
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t flex flex-col sm:flex-row gap-3">
            <button type="submit"
                class="inline-flex items-center justify-center px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                💾 Enregistrer les modifications
            </button>

            <!-- Formulaire de validation de l'inventaire -->
            <form action="<?php echo e(route('stock.inventories.validate', $inventory)); ?>" method="POST" class="inline-block">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-5 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                        onclick="return confirm('Êtes-vous sûr de vouloir valider cet inventaire ? Cette action est irréversible.')">
                    ✔ Valider l’inventaire
                </button>
            </form>
        </div>

    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('tbody tr').forEach(row => {
        const actualInput = row.querySelector('input[type="number"]');
        const theoreticalCell = row.querySelector('td:nth-child(3)');
        const differenceCell = row.querySelector('td:nth-child(5)');

        actualInput.addEventListener('input', function () {
            const theoretical = parseFloat(theoreticalCell.textContent.replace(/[^\d.-]/g, ''));
            const actual = parseFloat(this.value) || 0;
            const difference = actual - theoretical;

            if (!isNaN(difference)) {
                differenceCell.innerHTML =
                    `<span class="${difference < 0 ? 'text-red-600' : (difference > 0 ? 'text-green-600' : '')}">
                        ${difference.toFixed(2)}
                    </span>`;
            } else {
                differenceCell.innerHTML = '-';
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\inventories\edit.blade.php ENDPATH**/ ?>