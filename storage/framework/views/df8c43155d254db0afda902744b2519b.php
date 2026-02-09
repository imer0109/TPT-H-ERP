

<?php $__env->startSection('title', 'Tableau de bord Fournisseur'); ?>

<?php $__env->startSection('header', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Welcome message -->
    <div class="mb-6 rounded-lg bg-white p-6 shadow">
        <h1 class="text-2xl font-bold text-gray-800">Bienvenue, <?php echo e($supplier ? $supplier->raison_sociale : (auth()->user()->nom ?? 'Utilisateur')); ?></h1>
        <p class="mt-2 text-gray-600">Voici un aperçu de vos activités récentes avec notre entreprise.</p>
    </div>
    
    <!-- Key metrics -->
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="flex items-center">
                <div class="rounded-full bg-primary-100 p-3 text-primary-600">
                    <i class="fas fa-file-invoice fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Commandes totales</p>
                    <p class="text-2xl font-bold"><?php echo e($supplier ? $supplier->supplierOrders()->count() : 0); ?></p>
                </div>
            </div>
        </div>
        
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 text-green-600">
                    <i class="fas fa-truck-loading fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Livraisons</p>
                    <p class="text-2xl font-bold"><?php echo e($supplier ? $supplier->supplierDeliveries()->count() : 0); ?></p>
                </div>
            </div>
        </div>
        
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="flex items-center">
                <div class="rounded-full bg-yellow-100 p-3 text-yellow-600">
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Solde</p>
                    <p class="text-2xl font-bold"><?php echo e(number_format($outstandingBalance, 0, ',', ' ')); ?> FCFA</p>
                </div>
            </div>
        </div>
        
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="flex items-center">
                <div class="rounded-full bg-red-100 p-3 text-red-600">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Réclamations ouvertes</p>
                    <p class="text-2xl font-bold"><?php echo e($openIssues->count()); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent activities -->
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent orders -->
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Commandes récentes</h2>
                <a href="<?php echo e(route('supplier.portal.orders')); ?>" class="text-sm text-primary-600 hover:text-primary-800">
                    Voir tout
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Montant</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-900"><?php echo e($order->code); ?></td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500"><?php echo e($order->date_commande->format('d/m/Y')); ?></td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500"><?php echo e(number_format($order->montant_ttc, 0, ',', ' ')); ?> FCFA</td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm">
                                    <span class="inline-flex rounded-full bg-<?php echo e($order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : 'gray')); ?>-100 px-2 text-xs font-semibold leading-5 text-<?php echo e($order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : 'gray')); ?>-800">
                                        <?php echo e(ucfirst($order->statut)); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">Aucune commande trouvée</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Recent deliveries -->
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Livraisons récentes</h2>
                <a href="<?php echo e(route('supplier.portal.deliveries')); ?>" class="text-sm text-primary-600 hover:text-primary-800">
                    Voir tout
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">BL</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Commande</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php $__empty_1 = true; $__currentLoopData = $recentDeliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-900"><?php echo e($delivery->numero_bl); ?></td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500"><?php echo e($delivery->date_reception->format('d/m/Y')); ?></td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500"><?php echo e($delivery->order?->code ?? 'N/A'); ?></td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm">
                                    <span class="inline-flex rounded-full bg-<?php echo e($delivery->statut === 'completed' ? 'green' : ($delivery->statut === 'partial' ? 'yellow' : 'gray')); ?>-100 px-2 text-xs font-semibold leading-5 text-<?php echo e($delivery->statut === 'completed' ? 'green' : ($delivery->statut === 'partial' ? 'yellow' : 'gray')); ?>-800">
                                        <?php echo e(ucfirst($delivery->statut)); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">Aucune livraison trouvée</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Contracts and issues -->
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Active contracts -->
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Contrats actifs</h2>
                <a href="<?php echo e(route('supplier.portal.contracts')); ?>" class="text-sm text-primary-600 hover:text-primary-800">
                    Voir tout
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Contrat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Fin</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Jours restants</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php $__empty_1 = true; $__currentLoopData = $activeContracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-900"><?php echo e($contract->contract_number); ?></td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500"><?php echo e($contract->end_date->format('d/m/Y')); ?></td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500">
                                    <span class="<?php echo e($contract->days_until_expiry <= 30 ? 'text-red-600 font-bold' : 'text-gray-600'); ?>">
                                        <?php echo e($contract->days_until_expiry); ?> jours
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500">Aucun contrat actif</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Open issues -->
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Réclamations ouvertes</h2>
                <a href="<?php echo e(route('supplier.portal.issues')); ?>" class="text-sm text-primary-600 hover:text-primary-800">
                    Voir tout
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Titre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php $__empty_1 = true; $__currentLoopData = $openIssues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-900"><?php echo e($issue->titre); ?></td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500"><?php echo e(ucfirst(str_replace('_', ' ', $issue->type))); ?></td>
                                <td class="whitespace-nowrap px-4 py-2 text-sm text-gray-500"><?php echo e($issue->created_at->format('d/m/Y')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500">Aucune réclamation ouverte</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/fournisseurs/portal/index.blade.php ENDPATH**/ ?>