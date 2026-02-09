

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Historique des Modifications</h1>
            <p class="text-gray-600">Société: <?php echo e($company->raison_sociale); ?></p>
        </div>
        <a href="<?php echo e(route('companies.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Retour aux sociétés
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Heure</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $auditTrails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e($trail->created_at->format('d/m/Y H:i')); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php switch($trail->action):
                                case ('created'): ?>
                                    bg-green-100 text-green-800
                                    <?php break; ?>
                                <?php case ('updated'): ?>
                                    bg-blue-100 text-blue-800
                                    <?php break; ?>
                                <?php case ('deleted'): ?>
                                    bg-red-100 text-red-800
                                    <?php break; ?>
                                <?php case ('archived'): ?>
                                <?php case ('reactivated'): ?>
                                    bg-yellow-100 text-yellow-800
                                    <?php break; ?>
                                <?php case ('duplicated'): ?>
                                    bg-purple-100 text-purple-800
                                    <?php break; ?>
                            <?php endswitch; ?>">
                            <?php echo e(ucfirst($trail->action)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e($trail->user?->name ?? 'Système'); ?>

                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?php echo e($trail->description); ?>

                        <?php if($trail->changes): ?>
                            <div class="mt-1 text-xs">
                                <details>
                                    <summary class="text-blue-600 cursor-pointer">Voir les détails</summary>
                                    <pre class="bg-gray-100 p-2 mt-1 rounded"><?php echo e(json_encode($trail->changes, JSON_PRETTY_PRINT)); ?></pre>
                                </details>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Aucun historique trouvé pour cette société.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="px-6 py-4 bg-gray-50">
            <?php echo e($auditTrails->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\audit-trails\company.blade.php ENDPATH**/ ?>