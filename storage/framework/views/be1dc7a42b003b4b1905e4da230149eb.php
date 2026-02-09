

<?php $__env->startSection('title', 'État Actuel du Stock'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">État Actuel du Stock</h1>
        <div class="flex space-x-2">
            <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center space-x-2">
                <i class="fas fa-print"></i><span>Imprimer</span>
            </button>
            <button id="export-excel" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center space-x-2">
                <i class="fas fa-file-excel"></i><span>Exporter Excel</span>
            </button>
        </div>
    </div>

    
    <form action="<?php echo e(route('stock.reports.current-stock')); ?>" method="GET" class="bg-white shadow rounded-lg p-6 mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dépôt</label>
            <select name="warehouse_id" class="block w-full border border-gray-300 rounded-md p-2">
                <option value="">Tous les dépôts</option>
                <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($id); ?>" <?php echo e(request('warehouse_id') == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
            <input type="text" name="search" placeholder="Nom, référence ou description" value="<?php echo e(request('search')); ?>"
                   class="block w-full border border-gray-300 rounded-md p-2">
        </div>

        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded w-full">Filtrer</button>
        </div>
    </form>

    
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stock Actuel</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Prix d'Achat</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Valeur Stock</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Seuil d'Alerte</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $stockActuel = request('warehouse_id') 
                            ? $product->getStockInWarehouse(request('warehouse_id')) 
                            : $product->quantite;
                        $valeurStock = $stockActuel * $product->prix_unitaire;
                        $seuilAlerte = request('warehouse_id')
                            ? ($product->stockAlerts->where('warehouse_id', request('warehouse_id'))->first()?->seuil_min ?? '-')
                            : $product->seuil_alerte;
                    ?>
                    <tr>
                        <td class="px-6 py-3 text-sm text-gray-900"><?php echo e($product->reference); ?></td>
                        <td class="px-6 py-3 text-sm text-gray-900"><?php echo e($product->name); ?></td>
                        <td class="px-6 py-3 text-sm text-gray-900"><?php echo e($product->category->name ?? 'Non catégorisé'); ?></td>
                        <td class="px-6 py-3 text-right text-sm"><?php echo e(number_format($stockActuel, 2)); ?></td>
                        <td class="px-6 py-3 text-right text-sm"><?php echo e(number_format($product->prix_unitaire, 2)); ?></td>
                        <td class="px-6 py-3 text-right text-sm"><?php echo e(number_format($valeurStock, 2)); ?></td>
                        <td class="px-6 py-3 text-right text-sm"><?php echo e($seuilAlerte != '-' ? number_format($seuilAlerte, 2) : '-'); ?></td>
                        <td class="px-6 py-3">
                            <?php if($stockActuel <= 0): ?>
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">Rupture</span>
                            <?php elseif($seuilAlerte != '-' && $stockActuel <= $seuilAlerte): ?>
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">Alerte</span>
                            <?php else: ?>
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">Normal</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Aucun produit trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <th colspan="5" class="px-6 py-3 text-right text-sm font-medium">Valeur Totale du Stock:</th>
                    <th class="px-6 py-3 text-right text-sm font-medium">
                        <?php echo e(number_format($products->sum(function($product) {
                            $wid = request('warehouse_id');
                            $stockActuel = $wid
                                ? $product->getStockInWarehouse($wid)
                                : $product->quantite;
                            return $stockActuel * $product->prix_unitaire;
                        }), 2)); ?>

                    </th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-4">
        <?php echo e($products->appends(request()->query())->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('export-excel').addEventListener('click', function(e) {
        e.preventDefault();
        let url = '<?php echo e(route("stock.reports.current-stock")); ?>' + '?export=excel';
        const warehouseId = '<?php echo e(request("warehouse_id")); ?>';
        const search = '<?php echo e(request("search")); ?>';
        if (warehouseId) url += '&warehouse_id=' + warehouseId;
        if (search) url += '&search=' + search;
        window.location.href = url;
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\reports\current-stock.blade.php ENDPATH**/ ?>