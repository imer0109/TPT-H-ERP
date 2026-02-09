

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Contrats Fournisseurs</h1>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('fournisseurs.contracts.create')); ?>" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i> Nouveau Contrat
            </a>
            <a href="<?php echo e(route('fournisseurs.dashboard')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-chart-line mr-2"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="<?php echo e(route('fournisseurs.contracts.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
            </div>

            <div>
                <label for="fournisseur_id" class="block text-sm font-medium text-gray-700 mb-1">Fournisseur</label>
                <select name="fournisseur_id" id="fournisseur_id" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les fournisseurs</option>
                    <?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($fournisseur->id); ?>" <?php echo e(request('fournisseur_id') == $fournisseur->id ? 'selected' : ''); ?>>
                            <?php echo e($fournisseur->raison_sociale); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" id="status" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les statuts</option>
                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Actif</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>En attente</option>
                    <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Expiré</option>
                    <option value="terminated" <?php echo e(request('status') == 'terminated' ? 'selected' : ''); ?>>Résilié</option>
                </select>
            </div>

            <div class="flex items-end">
                <div class="flex items-center">
                    <input type="checkbox" name="expiring_soon" id="expiring_soon" value="1" 
                        class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                        <?php echo e(request('expiring_soon') ? 'checked' : ''); ?>>
                    <label for="expiring_soon" class="ml-2 block text-sm text-gray-700">
                        Expirant bientôt
                    </label>
                </div>
                <div class="ml-2">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-filter mr-2"></i> Filtrer
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Contracts List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contrat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fournisseur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valeur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?php echo e($contract->contract_number); ?></div>
                        <div class="text-sm text-gray-500"><?php echo e(Str::limit($contract->description, 30)); ?></div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <a href="<?php echo e(route('fournisseurs.show', $contract->fournisseur)); ?>" class="text-blue-600 hover:text-blue-900">
                            <?php echo e($contract->fournisseur->raison_sociale); ?>

                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?php echo e($contract->contract_type); ?>

                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <div>Du <?php echo e($contract->start_date->format('d/m/Y')); ?></div>
                        <div>Au <?php echo e($contract->end_date->format('d/m/Y')); ?></div>
                        <?php if($contract->isExpiringSoon()): ?>
                            <div class="text-yellow-600 font-medium">
                                Expire dans <?php echo e($contract->days_until_expiry); ?> jours
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?php if($contract->value): ?>
                            <?php echo e(number_format($contract->value, 2, ',', ' ')); ?> <?php echo e($contract->currency); ?>

                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <?php echo $contract->status_badge; ?>

                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('fournisseurs.contracts.show', $contract)); ?>" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('fournisseurs.contracts.edit', $contract)); ?>" class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('fournisseurs.contracts.destroy', $contract)); ?>" method="POST" 
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                        Aucun contrat trouvé
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="px-6 py-4">
            <?php echo e($contracts->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\contracts\index.blade.php ENDPATH**/ ?>