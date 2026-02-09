

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Modifier la Carte de Fidélité</h1>
        <a href="<?php echo e(route('clients.show', $loyaltyCard->client)); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Retour au Client
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="<?php echo e(route('clients.loyalty.update', $loyaltyCard)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="client_info" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <div class="font-medium"><?php echo e($loyaltyCard->client->nom_raison_sociale); ?></div>
                        <div class="text-sm text-gray-500"><?php echo e($loyaltyCard->client->code_client); ?></div>
                    </div>
                </div>
                
                <div>
                    <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro de Carte</label>
                    <div class="p-3 bg-gray-50 rounded-md font-mono">
                        <?php echo e($loyaltyCard->card_number); ?>

                    </div>
                </div>
                
                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700 mb-1">Points *</label>
                    <input type="number" name="points" id="points" min="0" value="<?php echo e(old('points', $loyaltyCard->points)); ?>" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <?php $__errorArgs = ['points'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div>
                    <label for="tier" class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php echo e($loyaltyCard->tier == 'bronze' ? 'bg-amber-100 text-amber-800' : ''); ?>

                            <?php echo e($loyaltyCard->tier == 'silver' ? 'bg-gray-100 text-gray-800' : ''); ?>

                            <?php echo e($loyaltyCard->tier == 'gold' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                            <?php echo e($loyaltyCard->tier == 'platinum' ? 'bg-blue-100 text-blue-800' : ''); ?>

                        ">
                            <?php echo e(ucfirst($loyaltyCard->tier)); ?>

                        </span>
                    </div>
                </div>
                
                <div>
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Date d'Expiration</label>
                    <input type="date" name="expires_at" id="expires_at" value="<?php echo e(old('expires_at', $loyaltyCard->expires_at ? $loyaltyCard->expires_at->format('Y-m-d') : '')); ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <?php $__errorArgs = ['expires_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut *</label>
                    <select name="status" id="status" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        <option value="active" <?php echo e(old('status', $loyaltyCard->status) == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e(old('status', $loyaltyCard->status) == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        <option value="suspended" <?php echo e(old('status', $loyaltyCard->status) == 'suspended' ? 'selected' : ''); ?>>Suspendue</option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div>
                    <label for="last_transaction_at" class="block text-sm font-medium text-gray-700 mb-1">Dernière Transaction</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <?php echo e($loyaltyCard->last_transaction_at ? $loyaltyCard->last_transaction_at->format('d/m/Y H:i') : 'Jamais'); ?>

                    </div>
                </div>
                
                <div>
                    <label for="issued_at" class="block text-sm font-medium text-gray-700 mb-1">Date d'Émission</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <?php echo e($loyaltyCard->issued_at ? $loyaltyCard->issued_at->format('d/m/Y') : 'N/A'); ?>

                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <a href="<?php echo e(route('clients.show', $loyaltyCard->client)); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Annuler
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
    
    <!-- Points Management -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Add Points -->
        <div class="bg-green-50 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Ajouter des Points</h2>
            <form action="<?php echo e(route('clients.loyalty.add-points', $loyaltyCard)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label for="add_points" class="block text-sm font-medium text-gray-700 mb-1">Nombre de Points</label>
                    <input type="number" name="points" id="add_points" min="1" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                </div>
                <div class="mb-4">
                    <label for="add_description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optionnel)</label>
                    <input type="text" name="description" id="add_description" maxlength="255"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i> Ajouter des Points
                </button>
            </form>
        </div>
        
        <!-- Redeem Points -->
        <div class="bg-red-50 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Utiliser des Points</h2>
            <form action="<?php echo e(route('clients.loyalty.redeem-points', $loyaltyCard)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label for="redeem_points" class="block text-sm font-medium text-gray-700 mb-1">Nombre de Points</label>
                    <input type="number" name="points" id="redeem_points" min="1" max="<?php echo e($loyaltyCard->points); ?>" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <p class="mt-1 text-sm text-gray-500">Points disponibles : <?php echo e($loyaltyCard->points); ?></p>
                </div>
                <div class="mb-4">
                    <label for="redeem_description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optionnel)</label>
                    <input type="text" name="description" id="redeem_description" maxlength="255"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                </div>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-minus mr-2"></i> Utiliser des Points
                </button>
            </form>
        </div>
    </div>
    
    <!-- Transaction History -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Historique des Transactions</h2>
        <?php if($loyaltyCard->transactions->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $loyaltyCard->transactions->sortByDesc('created_at')->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium <?php echo e($transaction->type == 'earned' ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($transaction->type == 'earned' ? '+' : '-'); ?><?php echo e($transaction->points); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo e($transaction->type == 'earned' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>

                                ">
                                    <?php echo e($transaction->type == 'earned' ? 'Gagnés' : 'Utilisés'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($transaction->description ?? 'N/A'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php if($loyaltyCard->transactions->count() > 10): ?>
                <div class="mt-4 text-center">
                    <a href="#" class="text-red-600 hover:text-red-800">
                        Voir tout l'historique (<?php echo e($loyaltyCard->transactions->count()); ?> transactions)
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-gray-500 italic">Aucune transaction enregistrée</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\loyalty\edit.blade.php ENDPATH**/ ?>