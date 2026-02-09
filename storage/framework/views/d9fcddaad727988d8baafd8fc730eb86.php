

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Commandes fournisseurs</h1>
        <a href="<?php echo e(route('fournisseurs.orders.create')); ?>" class="btn btn-primary">Nouvelle commande</a>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="text-left p-2">Code</th>
                    <th class="text-left p-2">Fournisseur</th>
                    <th class="text-left p-2">Date</th>
                    <th class="text-left p-2">Statut</th>
                    <th class="text-right p-2">Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-t">
                        <td class="p-2"><?php echo e($order->code); ?></td>
                        <td class="p-2"><?php echo e($order->fournisseur->raison_sociale ?? '-'); ?></td>
                        <td class="p-2"><?php echo e($order->date_commande); ?></td>
                        <td class="p-2"><?php echo e($order->statut); ?></td>
                        <td class="p-2 text-right"><?php echo e(number_format($order->montant_ttc, 2, ',', ' ')); ?> <?php echo e($order->devise); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="p-4 text-center text-gray-500">Aucune commande</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-4"><?php echo e($orders->links()); ?></div>
    </div>
    <div class="mt-4 text-sm text-gray-500">Liens: achats/stock, import PDF/Excel (à implémenter)</div>
    <div class="mt-2">
        <a href="<?php echo e(route('fournisseurs.index')); ?>" class="text-blue-600">Retour fournisseurs</a>
    </div>
    <div class="mt-4 flex gap-3">
        <a href="<?php echo e(route('fournisseurs.deliveries.index')); ?>" class="underline">Livraisons</a>
        <a href="<?php echo e(route('fournisseurs.payments.index')); ?>" class="underline">Paiements</a>
        <a href="<?php echo e(route('fournisseurs.issues.index')); ?>" class="underline">Réclamations</a>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\orders\index.blade.php ENDPATH**/ ?>