

<?php $__env->startSection('title', 'Intégrations - Portail Fournisseur'); ?>

<?php $__env->startSection('header', 'Intégrations avec les systèmes externes'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <h2 class="mb-4 text-2xl font-bold text-gray-800 sm:mb-0">Gérer les intégrations</h2>
    <a href="<?php echo e(route('supplier.portal.integrations.create')); ?>" 
       class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <i class="fas fa-plus mr-2"></i> Nouvelle intégration
    </a>
</div>

<!-- Filters -->
<div class="mb-6 rounded-lg bg-white p-4 shadow">
    <form action="<?php echo e(route('supplier.portal.integrations.index')); ?>" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div>
            <label for="integration_type" class="block text-sm font-medium text-gray-700">Type d'intégration</label>
            <select name="integration_type" id="integration_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <option value="">Tous les types</option>
                <option value="erp" <?php echo e(request('integration_type') == 'erp' ? 'selected' : ''); ?>>ERP</option>
                <option value="accounting" <?php echo e(request('integration_type') == 'accounting' ? 'selected' : ''); ?>>Comptabilité</option>
                <option value="inventory" <?php echo e(request('integration_type') == 'inventory' ? 'selected' : ''); ?>>Gestion de stock</option>
                <option value="custom" <?php echo e(request('integration_type') == 'custom' ? 'selected' : ''); ?>>Personnalisé</option>
            </select>
        </div>
        
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <option value="">Tous les statuts</option>
                <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Actif</option>
                <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactif</option>
            </select>
        </div>
        
        <div>
            <label for="sync_status" class="block text-sm font-medium text-gray-700">Statut de synchronisation</label>
            <select name="sync_status" id="sync_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <option value="">Tous les statuts</option>
                <option value="synced" <?php echo e(request('sync_status') == 'synced' ? 'selected' : ''); ?>>Synchronisé</option>
                <option value="pending" <?php echo e(request('sync_status') == 'pending' ? 'selected' : ''); ?>>En attente</option>
                <option value="failed" <?php echo e(request('sync_status') == 'failed' ? 'selected' : ''); ?>>Échoué</option>
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fas fa-filter mr-1"></i> Filtrer
            </button>
            
            <a href="<?php echo e(route('supplier.portal.integrations.index')); ?>" class="ml-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fas fa-times mr-1"></i> Réinitialiser
            </a>
        </div>
    </form>
</div>

<!-- Integrations table -->
<div class="rounded-lg bg-white shadow">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Système externe</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Statut</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Synchronisation</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Dernière synchronisation</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php $__empty_1 = true; $__currentLoopData = $integrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $integration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($integration->external_system); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e($integration->external_id ?? 'N/A'); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($integration->integration_type_formatted); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($integration->is_active): ?>
                                <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                    Actif
                                </span>
                            <?php else: ?>
                                <span class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                    Inactif
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($integration->sync_status == 'synced'): ?>
                                <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                    Synchronisé
                                </span>
                            <?php elseif($integration->sync_status == 'pending'): ?>
                                <span class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">
                                    En attente
                                </span>
                            <?php else: ?>
                                <span class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                    Échoué
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php if($integration->last_sync_at): ?>
                                <?php echo e($integration->last_sync_at->format('d/m/Y H:i')); ?>

                            <?php else: ?>
                                Jamais
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="<?php echo e(route('supplier.portal.integrations.show', $integration)); ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('supplier.portal.integrations.edit', $integration)); ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('supplier.portal.integrations.sync', $integration)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3" title="Synchroniser">
                                    <i class="fas fa-sync"></i>
                                </button>
                            </form>
                            <form action="<?php echo e(route('supplier.portal.integrations.destroy', $integration)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette intégration ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Aucune intégration trouvée.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if($integrations->hasPages()): ?>
        <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex flex-1 justify-between sm:hidden">
                    <?php echo e($integrations->links('vendor.pagination.simple-tailwind')); ?>

                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de <span class="font-medium"><?php echo e($integrations->firstItem()); ?></span> à <span class="font-medium"><?php echo e($integrations->lastItem()); ?></span> sur <span class="font-medium"><?php echo e($integrations->total()); ?></span> résultats
                        </p>
                    </div>
                    <div>
                        <?php echo e($integrations->links('vendor.pagination.tailwind')); ?>

                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Integration Information -->
<div class="mt-6 rounded-lg bg-blue-50 p-6 shadow-md">
    <h2 class="mb-4 text-lg font-bold text-gray-800">Informations sur les intégrations</h2>
    <div class="prose max-w-none">
        <p>Les intégrations permettent de synchroniser les données de votre fournisseur avec des systèmes externes tels que :</p>
        <ul class="list-disc pl-5">
            <li><strong>ERP</strong> : Intégration avec des systèmes de gestion des ressources de l'entreprise</li>
            <li><strong>Comptabilité</strong> : Synchronisation des données comptables et financières</li>
            <li><strong>Gestion de stock</strong> : Synchronisation des niveaux de stock et des mouvements</li>
            <li><strong>Personnalisé</strong> : Intégrations spécifiques selon vos besoins</li>
        </ul>
        <p class="mt-3"><strong>Note :</strong> La synchronisation peut être effectuée manuellement ou automatiquement selon la configuration du système externe.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\portal\integrations\index.blade.php ENDPATH**/ ?>