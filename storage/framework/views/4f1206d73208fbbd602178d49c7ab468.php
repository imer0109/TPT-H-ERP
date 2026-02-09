

<?php $__env->startSection('title', 'Détail de la réclamation'); ?>

<?php $__env->startSection('header', 'Détail de la réclamation'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Réclamation #<?php echo e($issue->id); ?></h2>
            <span class="inline-flex rounded-full bg-<?php echo e($issue->statut === 'resolved' ? 'green' : ($issue->statut === 'in_progress' ? 'blue' : ($issue->statut === 'open' ? 'yellow' : 'red'))); ?>-100 px-3 py-1 text-sm font-semibold leading-5 text-<?php echo e($issue->statut === 'resolved' ? 'green' : ($issue->statut === 'in_progress' ? 'blue' : ($issue->statut === 'open' ? 'yellow' : 'red'))); ?>-800">
                <?php echo e(ucfirst($issue->statut)); ?>

            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informations de la réclamation</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Titre:</span> <?php echo e($issue->titre); ?></p>
                    <p><span class="font-medium">Type:</span> <?php echo e(ucfirst(str_replace('_', ' ', $issue->type))); ?></p>
                    <p><span class="font-medium">Fournisseur:</span> <?php echo e($issue->fournisseur?->raison_sociale ?? 'N/A'); ?></p>
                    <p><span class="font-medium">Créé par:</span> <?php echo e($issue->createdBy?->name ?? 'N/A'); ?></p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Dates et statut</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Statut:</span> <?php echo e(ucfirst($issue->statut)); ?></p>
                    <p><span class="font-medium">Date de création:</span> <?php echo e($issue->created_at->format('d/m/Y H:i')); ?></p>
                    <?php if($issue->resolved_at): ?>
                        <p><span class="font-medium">Date de résolution:</span> <?php echo e($issue->resolved_at->format('d/m/Y H:i')); ?></p>
                    <?php endif; ?>
                    <?php if($issue->resolved_by): ?>
                        <p><span class="font-medium">Résolu par:</span> <?php echo e($issue->resolvedBy?->name ?? 'N/A'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Description</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-700"><?php echo e($issue->description); ?></p>
            </div>
        </div>
        
        <?php if($issue->resolution_notes): ?>
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Notes de résolution</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700"><?php echo e($issue->resolution_notes); ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if($issue->fournisseur && $issue->fournisseur->supplierOrders->count() > 0): ?>
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Commandes associées</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Montant TTC</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <?php $__empty_1 = true; $__currentLoopData = $issue->fournisseur->supplierOrders->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <a href="<?php echo e(route('supplier.portal.orders.show', $order)); ?>" class="text-blue-600 hover:text-blue-800"><?php echo e($order->code); ?></a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($order->date_commande->format('d/m/Y')); ?></td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span class="inline-flex rounded-full bg-<?php echo e($order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : ($order->statut === 'approved' ? 'blue' : 'red'))); ?>-100 px-2 text-xs font-semibold leading-5 text-<?php echo e($order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : ($order->statut === 'approved' ? 'blue' : 'red'))); ?>-800">
                                            <?php echo e(ucfirst($order->statut)); ?>

                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e(number_format($order->montant_ttc, 0, ',', ' ')); ?> FCFA</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Aucune commande associée trouvée</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="mt-6 flex justify-end">
            <a href="<?php echo e(route('supplier.portal.issues')); ?>" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\portal\issues\show.blade.php ENDPATH**/ ?>