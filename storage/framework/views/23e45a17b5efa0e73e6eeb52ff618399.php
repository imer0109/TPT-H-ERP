<?php $__env->startSection('title', 'Rapport des Pertes de Stock'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rapport des Pertes de Stock</h3>
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
                    <form action="<?php echo e(route('stock.reports.losses')); ?>" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
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
                                    <label>Date début</label>
                                    <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date fin</label>
                                    <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary form-control">Filtrer</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Résumé des Pertes</h5>
                        <p>
                            <?php if(request('warehouse_id')): ?>
                                Pertes pour le dépôt: <strong><?php echo e($warehouses[request('warehouse_id')]); ?></strong>
                            <?php else: ?>
                                Pertes pour tous les dépôts
                            <?php endif; ?>
                        </p>
                        <p>Période: 
                            <strong>
                                <?php echo e(request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') : 'Début'); ?> 
                                à 
                                <?php echo e(request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') : 'Aujourd\'hui'); ?>

                            </strong>
                        </p>
                        <p>Valeur totale des pertes: <strong><?php echo e(number_format($totalLoss, 2)); ?> FCFA</strong></p>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Dépôt</th>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Montant Total</th>
                                    <th>Motif</th>
                                    <th>Enregistré par</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $losses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loss): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loss->created_at->format('d/m/Y H:i')); ?></td>
                                        <td><?php echo e($loss->warehouse->nom); ?></td>
                                        <td><?php echo e($loss->product->nom); ?></td>
                                        <td class="text-right"><?php echo e(number_format($loss->quantite, 2)); ?></td>
                                        <td class="text-right"><?php echo e(number_format($loss->prix_unitaire, 2)); ?></td>
                                        <td class="text-right"><?php echo e(number_format($loss->montant_total, 2)); ?></td>
                                        <td><?php echo e($loss->motif); ?></td>
                                        <td><?php echo e($loss->createdBy->name); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Aucune perte enregistrée</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Total des Pertes:</th>
                                    <th class="text-right"><?php echo e(number_format($totalLoss, 2)); ?></th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <?php echo e($losses->appends(request()->query())->links()); ?>

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
        let url = '<?php echo e(route("stock.reports.losses")); ?>' + '?export=excel';
        
        // Ajouter les paramètres de filtrage actuels
        const warehouseId = '<?php echo e(request("warehouse_id")); ?>';
        const dateFrom = '<?php echo e(request("date_from")); ?>';
        const dateTo = '<?php echo e(request("date_to")); ?>';
        
        if (warehouseId) url += '&warehouse_id=' + warehouseId;
        if (dateFrom) url += '&date_from=' + dateFrom;
        if (dateTo) url += '&date_to=' + dateTo;
        
        window.location.href = url;
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\reports\losses.blade.php ENDPATH**/ ?>