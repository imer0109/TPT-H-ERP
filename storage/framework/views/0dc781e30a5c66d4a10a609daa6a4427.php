

<?php $__env->startSection('title', 'Contrats Fournisseur'); ?>

<?php $__env->startSection('header', 'Contrats'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Liste des contrats</h2>
    </div>
    
    <div class="rounded-lg bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Numéro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date début</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Jours restants</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900"><?php echo e($contract->contract_number); ?></td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($contract->contract_type); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e(Str::limit($contract->description, 50)); ?></td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($contract->start_date->format('d/m/Y')); ?></td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($contract->end_date->format('d/m/Y')); ?></td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                <span class="<?php echo e($contract->days_until_expiry <= 30 ? 'text-red-600 font-bold' : 'text-gray-600'); ?>">
                                    <?php echo e($contract->days_until_expiry); ?> jours
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full bg-<?php echo e($contract->status === 'active' ? 'green' : ($contract->status === 'pending' ? 'yellow' : ($contract->status === 'expired' ? 'red' : 'gray'))); ?>-100 px-2 text-xs font-semibold leading-5 text-<?php echo e($contract->status === 'active' ? 'green' : ($contract->status === 'pending' ? 'yellow' : ($contract->status === 'expired' ? 'red' : 'gray'))); ?>-800">
                                    <?php echo e(ucfirst($contract->status)); ?>

                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                <a href="<?php echo e(route('supplier.portal.contracts.show', $contract)); ?>" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Aucun contrat trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            <?php echo e($contracts->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\portal\contracts.blade.php ENDPATH**/ ?>