

<?php $__env->startSection('title', 'Détail du contrat'); ?>

<?php $__env->startSection('header', 'Détail du contrat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Contrat #<?php echo e($contract->id); ?></h2>
            <span class="inline-flex rounded-full bg-<?php echo e($contract->statut === 'active' ? 'green' : ($contract->statut === 'pending' ? 'yellow' : 'red')); ?>-100 px-3 py-1 text-sm font-semibold leading-5 text-<?php echo e($contract->statut === 'active' ? 'green' : ($contract->statut === 'pending' ? 'yellow' : 'red')); ?>-800">
                <?php echo e(ucfirst($contract->statut)); ?>

            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informations du contrat</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Titre:</span> <?php echo e($contract->titre); ?></p>
                    <p><span class="font-medium">Fournisseur:</span> <?php echo e($contract->fournisseur?->raison_sociale ?? 'N/A'); ?></p>
                    <p><span class="font-medium">Responsable:</span> <?php echo e($contract->responsible?->name ?? 'N/A'); ?></p>
                    <p><span class="font-medium">Type:</span> <?php echo e($contract->type); ?></p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Dates et statut</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Date de début:</span> <?php echo e($contract->start_date->format('d/m/Y')); ?></p>
                    <p><span class="font-medium">Date de fin:</span> <?php echo e($contract->end_date->format('d/m/Y')); ?></p>
                    <p><span class="font-medium">Statut:</span> <?php echo e(ucfirst($contract->statut)); ?></p>
                    <p><span class="font-medium">Date de création:</span> <?php echo e($contract->created_at->format('d/m/Y H:i')); ?></p>
                </div>
            </div>
        </div>
        
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Description</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-700"><?php echo e($contract->description ?? 'Aucune description fournie'); ?></p>
            </div>
        </div>
        
        <?php if($contract->fournisseur && $contract->fournisseur->supplierOrders->count() > 0): ?>
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
                            <?php $__empty_1 = true; $__currentLoopData = $contract->fournisseur->supplierOrders->whereBetween('date_commande', [$contract->start_date, $contract->end_date]); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
            <a href="<?php echo e(route('supplier.portal.contracts')); ?>" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\portal\contracts\show.blade.php ENDPATH**/ ?>