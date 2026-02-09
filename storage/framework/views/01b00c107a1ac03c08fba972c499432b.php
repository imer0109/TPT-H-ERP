<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Bons de Commande (BOC)</h1>
                <p class="text-gray-600 mt-1">Gestion des bons de commande fournisseurs</p>
            </div>
            <a href="<?php echo e(route('purchases.orders.create')); ?>" 
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-plus mr-2"></i>Nouveau BOC
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="<?php echo e(route('purchases.orders.index')); ?>" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                       placeholder="Code, fournisseur..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Tous les statuts</option>
                    <?php $__currentLoopData = $statuts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statut): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($statut); ?>" <?php echo e(request('statut') == $statut ? 'selected' : ''); ?>>
                            <?php echo e($statut); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fournisseur</label>
                <select name="fournisseur_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Tous les fournisseurs</option>
                    <?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($fournisseur->id); ?>" <?php echo e(request('fournisseur_id') == $fournisseur->id ? 'selected' : ''); ?>>
                            <?php echo e($fournisseur->raison_sociale); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Agence</label>
                <select name="agency_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Toutes les agences</option>
                    <?php $__currentLoopData = $agencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($agency->id); ?>" <?php echo e(request('agency_id') == $agency->id ? 'selected' : ''); ?>>
                            <?php echo e($agency->nom); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg mr-2">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
                <a href="<?php echo e(route('purchases.orders.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des commandes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code BOC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fournisseur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agence</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($order->code); ?></div>
                                <?php if($order->purchaseRequest): ?>
                                    <div class="text-xs text-blue-600">DA: <?php echo e($order->purchaseRequest->code); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($order->fournisseur->raison_sociale ?? '-'); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($order->fournisseur->contact_principal ?? '-'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($order->date_commande->format('d/m/Y')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e(number_format($order->montant_ttc, 0, ',', ' ')); ?> <?php echo e($order->devise); ?></div>
                                <div class="text-xs text-gray-500">HT: <?php echo e(number_format($order->montant_ht, 0, ',', ' ')); ?> <?php echo e($order->devise); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $statusColors = [
                                        'Brouillon' => 'bg-gray-100 text-gray-800',
                                        'En attente' => 'bg-yellow-100 text-yellow-800',
                                        'Envoyé' => 'bg-blue-100 text-blue-800',
                                        'Confirmé' => 'bg-green-100 text-green-800',
                                        'Livré' => 'bg-purple-100 text-purple-800',
                                        'Clôturé' => 'bg-gray-100 text-gray-800',
                                        'Annulé' => 'bg-red-100 text-red-800'
                                    ];
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statusColors[$order->statut] ?? 'bg-gray-100 text-gray-800'); ?>">
                                    <?php echo e($order->statut); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($order->agency->nom ?? '-'); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="<?php echo e(route('purchases.orders.show', $order)); ?>" 
                                       class="text-blue-600 hover:text-blue-800" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if(in_array($order->statut, ['Brouillon', 'En attente'])): ?>
                                        <a href="<?php echo e(route('purchases.orders.edit', $order)); ?>" 
                                           class="text-yellow-600 hover:text-yellow-800" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('purchases.orders.pdf', $order)); ?>" 
                                       class="text-green-600 hover:text-green-800" title="PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <?php if($order->statut === 'Confirmé'): ?>
                                        <a href="<?php echo e(route('purchases.orders.create-delivery', $order)); ?>" 
                                           class="text-purple-600 hover:text-purple-800" title="Créer livraison">
                                            <i class="fas fa-truck"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                                <p>Aucun bon de commande trouvé</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($orders->hasPages()): ?>
            <div class="px-6 py-4 border-t border-gray-200">
                <?php echo e($orders->appends(request()->all())->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\purchases\orders\index.blade.php ENDPATH**/ ?>