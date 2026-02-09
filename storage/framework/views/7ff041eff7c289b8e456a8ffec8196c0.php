

<?php $__env->startSection('title', 'Historique des Mouvements de Stock'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Historique des Mouvements de Stock</h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-success btn-sm" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimer
                        </a>
                        <a href="#" class="btn btn-primary btn-sm" id="export-excel">
                            <i class="fas fa-file-excel"></i> Exporter Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('stock.reports.movements-history')); ?>" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Dépôt</label>
                                    <select name="warehouse_id" class="form-control">
                                        <option value="">Tous les dépôts</option>
                                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($id); ?>" <?php echo e(request('warehouse_id') == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Produit</label>
                                    <select name="product_id" class="form-control">
                                        <option value="">Tous les produits</option>
                                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($id); ?>" <?php echo e(request('product_id') == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select name="type" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="entree" <?php echo e(request('type') == 'entree' ? 'selected' : ''); ?>>Entrée</option>
                                        <option value="sortie" <?php echo e(request('type') == 'sortie' ? 'selected' : ''); ?>>Sortie</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Date début</label>
                                    <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Date fin</label>
                                    <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filtrer
                                </button>
                                <a href="<?php echo e(route('stock.reports.movements-history')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-sync"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Dépôt</th>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Montant Total</th>
                                    <th>Motif</th>
                                    <th>Créé par</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($movement->created_at->format('d/m/Y H:i')); ?></td>
                                        <td>
                                            <?php if($movement->type == 'entree'): ?>
                                                <span class="badge badge-success">Entrée</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Sortie</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($movement->warehouse->name); ?></td>
                                        <td><?php echo e($movement->product->name); ?></td>
                                        <td class="text-right"><?php echo e(number_format($movement->quantite, 2)); ?></td>
                                        <td class="text-right"><?php echo e(number_format($movement->prix_unitaire, 2)); ?></td>
                                        <td class="text-right"><?php echo e(number_format($movement->montant_total, 2)); ?></td>
                                        <td><?php echo e($movement->motif); ?></td>
                                        <td><?php echo e($movement->createdBy->name); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Aucun mouvement trouvé</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total:</th>
                                    <th class="text-right">
                                        <?php echo e(number_format($movements->sum(function($movement) {
                                            return $movement->type == 'entree' ? $movement->quantite : -$movement->quantite;
                                        }), 2)); ?>

                                    </th>
                                    <th></th>
                                    <th class="text-right">
                                        <?php echo e(number_format($movements->sum(function($movement) {
                                            return $movement->type == 'entree' ? $movement->montant_total : -$movement->montant_total;
                                        }), 2)); ?>

                                    </th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <?php echo e($movements->appends(request()->query())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('export-excel').addEventListener('click', function(e) {
        e.preventDefault();
        let url = '<?php echo e(route("stock.reports.movements-history")); ?>' + '?export=excel';
        
        // Ajouter les paramètres de filtrage actuels
        const warehouseId = '<?php echo e(request("warehouse_id")); ?>';
        const productId = '<?php echo e(request("product_id")); ?>';
        const type = '<?php echo e(request("type")); ?>';
        const dateFrom = '<?php echo e(request("date_from")); ?>';
        const dateTo = '<?php echo e(request("date_to")); ?>';
        
        if (warehouseId) url += '&warehouse_id=' + warehouseId;
        if (productId) url += '&product_id=' + productId;
        if (type) url += '&type=' + type;
        if (dateFrom) url += '&date_from=' + dateFrom;
        if (dateTo) url += '&date_to=' + dateTo;
        
        window.location.href = url;
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\reports\movements-history.blade.php ENDPATH**/ ?>