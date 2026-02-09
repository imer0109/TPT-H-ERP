

<?php $__env->startSection('title', 'Valorisation du Stock'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Valorisation du Stock</h3>
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
                    <form action="<?php echo e(route('stock.reports.valuation')); ?>" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary form-control">Filtrer</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Information</h5>
                        <p>
                            <?php if(request('warehouse_id')): ?>
                                Valorisation du stock pour le dépôt: <strong><?php echo e($warehouses[request('warehouse_id')]); ?></strong>
                            <?php else: ?>
                                Valorisation du stock pour tous les dépôts
                            <?php endif; ?>
                        </p>
                        <p>Valeur totale du stock: <strong><?php echo e(number_format($totalValuation, 2)); ?> FCFA</strong></p>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Produit</th>
                                    <th>Prix d'Achat</th>
                                    <th>Quantité en Stock</th>
                                    <th>Valeur du Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($product->reference); ?></td>
                                        <td><?php echo e($product->nom); ?></td>
                                        <td><?php echo e($product->category->name ?? 'Non catégorisé'); ?></td>
                                        <td class="text-right"><?php echo e(number_format($product->prix_achat, 2)); ?></td>
                                        <td class="text-right"><?php echo e(number_format($product->stock_actuel, 2)); ?></td>
                                        <td class="text-right"><?php echo e(number_format($product->valeur_stock, 2)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Aucun produit trouvé</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Valeur Totale du Stock:</th>
                                    <th class="text-right"><?php echo e(number_format($totalValuation, 2)); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <?php echo e($products->appends(request()->query())->links()); ?>

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
        let url = '<?php echo e(route("stock.reports.valuation")); ?>' + '?export=excel';
        
        // Ajouter les paramètres de filtrage actuels
        const warehouseId = '<?php echo e(request("warehouse_id")); ?>';
        
        if (warehouseId) {
            url += '&warehouse_id=' + warehouseId;
        }
        
        window.location.href = url;
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\reports\valuation.blade.php ENDPATH**/ ?>