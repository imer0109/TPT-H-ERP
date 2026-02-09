

<?php $__env->startSection('title', 'Commandes Fournisseur'); ?>

<?php $__env->startSection('header', 'Commandes'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex flex-col items-center justify-between md:flex-row">
        <h2 class="text-xl font-bold text-gray-800">Liste des commandes</h2>
        
        <div class="mt-4 flex w-full md:mt-0 md:w-auto">
            <form method="GET" action="<?php echo e(route('supplier.portal.orders')); ?>" class="flex w-full space-x-2 md:w-auto">
                <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm md:w-auto">
                    <option value="">Tous les statuts</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>En attente</option>
                    <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approuvé</option>
                    <option value="delivered" <?php echo e(request('status') == 'delivered' ? 'selected' : ''); ?>>Livré</option>
                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Annulé</option>
                </select>
                
                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" 
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm md:w-auto">
                
                <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" 
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm md:w-auto">
                
                <button type="submit" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-filter mr-1"></i> Filtrer
                </button>
                
                <a href="<?php echo e(route('supplier.portal.orders')); ?>" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-times mr-1"></i> Réinitialiser
                </a>
            </form>
        </div>
    </div>
    
    <div class="rounded-lg bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Agence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Montant HT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Montant TTC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900"><?php echo e($order->code); ?></td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($order->date_commande->format('d/m/Y')); ?></td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($order->agency?->nom ?? 'N/A'); ?></td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e(number_format($order->montant_ht, 0, ',', ' ')); ?> FCFA</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e(number_format($order->montant_ttc, 0, ',', ' ')); ?> FCFA</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full bg-<?php echo e($order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : ($order->statut === 'approved' ? 'blue' : 'red'))); ?>-100 px-2 text-xs font-semibold leading-5 text-<?php echo e($order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : ($order->statut === 'approved' ? 'blue' : 'red'))); ?>-800">
                                    <?php echo e(ucfirst($order->statut)); ?>

                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                <a href="<?php echo e(route('supplier.portal.orders.show', $order)); ?>" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Aucune commande trouvée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            <?php echo e($orders->appends(request()->query())->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\portal\orders.blade.php ENDPATH**/ ?>