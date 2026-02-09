<?php $__env->startSection('title', 'Détails du Dépôt'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Détails du Dépôt: <?php echo e($warehouse->nom); ?></h1>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('stock.warehouses.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <a href="<?php echo e(route('stock.warehouses.edit', $warehouse)); ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition">
                <i class="fas fa-edit"></i> Modifier
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        
        <div class="bg-white shadow rounded-lg">
            <div class="bg-blue-600 text-white px-4 py-2 rounded-t-lg">
                <h5 class="font-semibold">Informations Générales</h5>
            </div>
            <div class="p-4">
                <table class="w-full text-sm text-gray-700">
                    <tr class="border-b">
                        <th class="text-left py-2 w-1/3">Code</th>
                        <td><?php echo e($warehouse->code); ?></td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-2">Nom</th>
                        <td><?php echo e($warehouse->nom); ?></td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-2">Description</th>
                        <td><?php echo e($warehouse->description ?: 'Non spécifiée'); ?></td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-2">Type</th>
                        <td><?php echo e($warehouse->type); ?></td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-2">Adresse</th>
                        <td><?php echo e($warehouse->adresse ?: 'Non spécifiée'); ?></td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-2">Statut</th>
                        <td>
                            <?php if($warehouse->actif): ?>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Actif</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Inactif</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-2">Créé par</th>
                        <td><?php echo e($warehouse->createdBy->name ?? 'Utilisateur inconnu'); ?></td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-2">Date de création</th>
                        <td><?php echo e($warehouse->created_at->format('d/m/Y H:i')); ?></td>
                    </tr>
                    <tr>
                        <th class="text-left py-2">Dernière mise à jour</th>
                        <td><?php echo e($warehouse->updated_at->format('d/m/Y H:i')); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        
        <div class="space-y-6">

            
            <div class="bg-white shadow rounded-lg">
                <div class="bg-indigo-500 text-white px-4 py-2 rounded-t-lg">
                    <h5 class="font-semibold">Statistiques</h5>
                </div>
                <div class="p-4 grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg flex items-center">
                        <i class="fas fa-boxes text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-600">Mouvements de Stock</p>
                            <p class="text-xl font-bold"><?php echo e($warehouse->stockMovements->count()); ?></p>
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-600">Alertes</p>
                            <p class="text-xl font-bold"><?php echo e($warehouse->alerts->count()); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white shadow rounded-lg">
                <div class="bg-green-500 text-white px-4 py-2 rounded-t-lg">
                    <h5 class="font-semibold">Actions</h5>
                </div>
                <div class="p-4 grid grid-cols-2 gap-4">
                    <a href="<?php echo e(route('stock.movements.create', ['warehouse_id' => $warehouse->id])); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded text-center transition">
                        <i class="fas fa-plus mr-2"></i> Nouveau Mouvement
                    </a>
                    <a href="<?php echo e(route('stock.inventories.create', ['warehouse_id' => $warehouse->id])); ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded text-center transition">
                        <i class="fas fa-clipboard-list mr-2"></i> Nouvel Inventaire
                    </a>
                    <a href="<?php echo e(route('stock.transfers.create', ['source_id' => $warehouse->id])); ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-3 rounded text-center transition">
                        <i class="fas fa-exchange-alt mr-2"></i> Nouveau Transfert
                    </a>
                    <a href="<?php echo e(route('stock.reports.current-stock', ['warehouse_id' => $warehouse->id])); ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded text-center transition">
                        <i class="fas fa-chart-bar mr-2"></i> Rapport de Stock
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\warehouses\show.blade.php ENDPATH**/ ?>