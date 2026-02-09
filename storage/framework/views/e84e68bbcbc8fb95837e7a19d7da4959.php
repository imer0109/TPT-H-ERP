

<?php $__env->startSection('title', 'Inventaires de Stock'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="bg-white shadow-lg rounded-lg">
        <!-- Header -->
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 class="text-lg font-semibold">Inventaires de Stock</h3>
            <a href="<?php echo e(route('stock.inventories.create')); ?>" 
               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Nouvel Inventaire
            </a>
        </div>

        <div class="p-6">
            <!-- Formulaire de filtre -->
            <form action="<?php echo e(route('stock.inventories.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Statut -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="status" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Tous</option>
                        <option value="en_cours" <?php echo e(request('status') == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                        <option value="valide" <?php echo e(request('status') == 'valide' ? 'selected' : ''); ?>>Validé</option>
                    </select>
                </div>

                <!-- Dépôt -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dépôt</label>
                    <select name="warehouse_id" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Tous</option>
                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($id); ?>" <?php echo e(old('warehouse_id') == $id ? 'selected' : ''); ?>>
                                    <?php echo e($name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Période -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Période</label>
                    <div class="flex items-center gap-2 mt-1">
                        <input type="date" name="date_start" value="<?php echo e(request('date_start')); ?>"
                               class="block w-1/2 border border-gray-300 rounded-md shadow-sm px-2 py-2 
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <span class="text-gray-500">à</span>
                        <input type="date" name="date_end" value="<?php echo e(request('date_end')); ?>"
                               class="block w-1/2 border border-gray-300 rounded-md shadow-sm px-2 py-2 
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <!-- Bouton Filtrer -->
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Filtrer
                    </button>
                </div>
            </form>

            <!-- Tableau -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Référence</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Date</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Dépôt</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Statut</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Créé par</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Validé par</th>
                            <th class="px-4 py-2 text-center font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $inventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-4 py-2"><?php echo e($inventory->reference); ?></td>
                                <td class="px-4 py-2"><?php echo e($inventory->date->format('d/m/Y')); ?></td>
                                <td class="px-4 py-2"><?php echo e($inventory->warehouse ? $inventory->warehouse->name : 'N/A'); ?></td>
                                <td class="px-4 py-2">
                                    <?php if($inventory->status == 'en_cours'): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">
                                            En cours
                                        </span>
                                    <?php elseif($inventory->status == 'valide'): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                            Validé
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2"><?php echo e($inventory->createdBy->name); ?></td>
                                <td class="px-4 py-2"><?php echo e($inventory->validatedBy->name ?? '-'); ?></td>
                                <td class="px-4 py-2 flex justify-center gap-2">
                                    <a href="<?php echo e(route('stock.inventories.show', $inventory)); ?>" 
                                       class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <?php if($inventory->status == 'en_cours'): ?>
                                        <a href="<?php echo e(route('stock.inventories.edit', $inventory)); ?>" 
                                           class="px-2 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="<?php echo e(route('stock.inventories.validate', $inventory)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" 
                                                    class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir valider cet inventaire?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-gray-500">Aucun inventaire trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <?php echo e($inventories->appends(request()->query())->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\inventories\index.blade.php ENDPATH**/ ?>