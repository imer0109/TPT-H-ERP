<?php $__env->startSection('title', 'Mouvements de Stock'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Mouvements de Stock</h1>
        <div class="flex space-x-3">
            <a href="<?php echo e(route('stock.movements.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                Nouveau Mouvement
            </a>
            <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                Importer
            </button>
            <a href="<?php echo e(route('stock.movements.export')); ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                Exporter
            </a>
        </div>
    </div>

    <!-- Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Filtres -->
    <form action="<?php echo e(route('stock.movements.index')); ?>" method="GET" class="bg-white shadow sm:rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Type de mouvement</label>
                <select name="type" id="type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                    <option value="">Tous</option>
                    <option value="entree" <?php echo e(request('type') == 'entree' ? 'selected' : ''); ?>>Entrée</option>
                    <option value="sortie" <?php echo e(request('type') == 'sortie' ? 'selected' : ''); ?>>Sortie</option>
                </select>
            </div>
            <div>
                <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Dépôt</label>
                <select name="warehouse_id" id="warehouse_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                    <option value="">Tous</option>
                    <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($id); ?>" <?php echo e(request('warehouse_id') == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label for="date_start" class="block text-sm font-medium text-gray-700">Date début</label>
                <input type="date" name="date_start" id="date_start" value="<?php echo e(request('date_start')); ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="date_end" class="block text-sm font-medium text-gray-700">Date fin</label>
                <input type="date" name="date_end" id="date_end" value="<?php echo e(request('date_end')); ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
            </div>
        </div>
        <div class="mt-4 flex justify-end space-x-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">Filtrer</button>
            <a href="<?php echo e(route('stock.movements.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">Réinitialiser</a>
        </div>
    </form>

    <!-- Tableau responsive -->
    <div class="overflow-x-auto bg-white shadow sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Référence</th>
                    <th class="px-6 py-3 border-r">Date</th>
                    <th class="px-6 py-3 border-r">Type</th>
                    <th class="px-6 py-3 border-r">Produit</th>
                    <th class="px-6 py-3 border-r">Dépôt</th>
                    <th class="px-6 py-3 border-r">Quantité</th>
                    <th class="px-6 py-3 border-r">Prix Unitaire</th>
                    <!-- <th class="px-6 py-3 border-r">Montant Total</th> -->
                    <th class="px-6 py-3 border-r">Statut</th>
                    <th class="px-6 py-3 border-r">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-900 border-r"><?php echo e($movement->reference); ?></td>
                        <td class="px-6 py-4 text-sm border-r"><?php echo e($movement->created_at->format('d/m/Y H:i')); ?></td>
                        <td class="px-6 py-4 border-r">
                            <span class="px-2 inline-flex text-xs font-semibold rounded-full <?php echo e($movement->type == 'entree' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e(ucfirst($movement->type)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm border-r"><?php echo e($movement->product->name ?? 'N/A'); ?></td>
                        <td class="px-6 py-4 text-sm border-r"><?php echo e($movement->warehouse->nom ?? 'N/A'); ?></td>
                        <td class="px-6 py-4 text-sm border-r"><?php echo e(number_format($movement->quantite, 0, ',', ' ')); ?></td>
                        <td class="px-6 py-4 text-sm border-r"><?php echo e(number_format($movement->prix_unitaire, 0, ',', ' ')); ?></td>
                        <!-- <td class="px-6 py-4 text-sm border-r"><?php echo e(number_format($movement->montant_total, 2, ',', ' ')); ?></td> -->
                        <td class="px-6 py-4 border-r">
                            <span class="px-2 inline-flex text-xs font-semibold rounded-full <?php echo e($movement->validated_by ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                <?php echo e($movement->validated_by ? 'Validé' : 'En attente'); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium flex space-x-2 border-r">
                            <a href="<?php echo e(route('stock.movements.show', $movement)); ?>" class="text-blue-600 hover:text-blue-900 transition">Détails</a>
                            <?php if(!$movement->validated_by && auth()->user()->can('validate', $movement)): ?>
                                <form action="<?php echo e(route('stock.movements.validate', $movement)); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-green-600 hover:text-green-900 transition">Valider</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">Aucun mouvement de stock trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex justify-center">
        <?php echo e($movements->appends(request()->query())->links('pagination::tailwind')); ?>

    </div>
</div>

<!-- Modal Import -->
<div id="importModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700">&times;</button>
        <h2 class="text-lg font-semibold mb-4">Importer des Mouvements de Stock</h2>
        <form action="<?php echo e(route('stock.movements.import')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700">Fichier Excel</label>
                <input type="file" name="file" id="file" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: .xlsx, .xls</p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">
                    Annuler
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                    Importer
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\movements\index.blade.php ENDPATH**/ ?>