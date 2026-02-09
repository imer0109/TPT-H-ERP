

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Livraisons fournisseurs</h1>
        <a href="<?php echo e(route('fournisseurs.deliveries.create')); ?>" class="btn btn-primary">Nouvelle livraison</a>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="p-2 text-left">Date</th>
                    <th class="p-2 text-left">Fournisseur</th>
                    <th class="p-2 text-left">Dépôt</th>
                    <th class="p-2 text-left">Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-t">
                        <td class="p-2"><?php echo e($d->date_reception); ?></td>
                        <td class="p-2"><?php echo e($d->fournisseur->raison_sociale ?? '-'); ?></td>
                        <td class="p-2"><?php echo e($d->warehouse->nom ?? '-'); ?></td>
                        <td class="p-2">
                            <span class="px-2 py-1 text-xs rounded
                                <?php if($d->statut == 'received'): ?> bg-green-100 text-green-800
                                <?php elseif($d->statut == 'partial'): ?> bg-yellow-100 text-yellow-800
                                <?php else: ?> bg-red-100 text-red-800
                                <?php endif; ?>">
                                <?php echo e(ucfirst($d->statut)); ?>

                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="p-4 text-center text-gray-500">Aucune livraison</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-4"><?php echo e($deliveries->links()); ?></div>
    </div>
    <div class="mt-4">
        <a href="<?php echo e(route('fournisseurs.orders.index')); ?>" class="text-blue-600">Voir commandes</a>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\deliveries\index.blade.php ENDPATH**/ ?>