

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Cartes de Fidélité</h1>
        <a href="<?php echo e(route('clients.dashboard')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Retour au Dashboard
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="<?php echo e(route('clients.loyalty.index')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                       placeholder="Numéro de carte ou nom du client">
            </div>
            
            <div>
                <label for="tier" class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
                <select name="tier" id="tier" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <option value="">Tous</option>
                    <option value="bronze" <?php echo e(request('tier') == 'bronze' ? 'selected' : ''); ?>>Bronze</option>
                    <option value="silver" <?php echo e(request('tier') == 'silver' ? 'selected' : ''); ?>>Argent</option>
                    <option value="gold" <?php echo e(request('tier') == 'gold' ? 'selected' : ''); ?>>Or</option>
                    <option value="platinum" <?php echo e(request('tier') == 'platinum' ? 'selected' : ''); ?>>Platine</option>
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" id="status" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <option value="">Tous</option>
                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    <option value="suspended" <?php echo e(request('status') == 'suspended' ? 'selected' : ''); ?>>Suspendue</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
                <a href="<?php echo e(route('clients.loyalty.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    <i class="fas fa-times mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Loyalty Cards Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro de Carte</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière Transaction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $loyaltyCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($card->client->nom_raison_sociale); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e($card->client->code_client); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($card->card_number); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($card->points); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo e($card->tier == 'bronze' ? 'bg-amber-100 text-amber-800' : ''); ?>

                                <?php echo e($card->tier == 'silver' ? 'bg-gray-100 text-gray-800' : ''); ?>

                                <?php echo e($card->tier == 'gold' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                <?php echo e($card->tier == 'platinum' ? 'bg-blue-100 text-blue-800' : ''); ?>

                            ">
                                <?php echo e(ucfirst($card->tier)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo e($card->status == 'active' ? 'bg-green-100 text-green-800' : ''); ?>

                                <?php echo e($card->status == 'inactive' ? 'bg-gray-100 text-gray-800' : ''); ?>

                                <?php echo e($card->status == 'suspended' ? 'bg-red-100 text-red-800' : ''); ?>

                            ">
                                <?php echo e(ucfirst($card->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($card->last_transaction_at ? $card->last_transaction_at->format('d/m/Y H:i') : 'Jamais'); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="<?php echo e(route('clients.loyalty.edit', $card)); ?>" class="text-red-600 hover:text-red-900 mr-3">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="<?php echo e(route('clients.loyalty.destroy', $card)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte de fidélité?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            Aucune carte de fidélité trouvée.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <?php echo e($loyaltyCards->links()); ?>

        </div>
    </div>
    
    <!-- Statistics -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-id-card fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Cartes</p>
                    <p class="text-2xl font-bold"><?php echo e($loyaltyCards->total()); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-coins fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Points Totals</p>
                    <p class="text-2xl font-bold"><?php echo e($loyaltyCards->sum('points')); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Cartes Actives</p>
                    <p class="text-2xl font-bold"><?php echo e($loyaltyCards->where('status', 'active')->count()); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-crown fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Membres Platine</p>
                    <p class="text-2xl font-bold"><?php echo e($loyaltyCards->where('tier', 'platinum')->count()); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\loyalty\index.blade.php ENDPATH**/ ?>