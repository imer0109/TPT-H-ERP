

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nouvelle Carte de Fidélité</h1>
        <a href="<?php echo e(route('clients.show', $client)); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Retour au Client
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="<?php echo e(route('clients.loyalty.store', $client)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="client_info" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <div class="font-medium"><?php echo e($client->nom_raison_sociale); ?></div>
                        <div class="text-sm text-gray-500"><?php echo e($client->code_client); ?></div>
                    </div>
                </div>
                
                <div>
                    <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro de Carte</label>
                    <div class="p-3 bg-gray-50 rounded-md font-mono">
                        <?php echo e(\App\Models\LoyaltyCard::generateCardNumber()); ?>

                    </div>
                    <p class="mt-1 text-sm text-gray-500">Le numéro sera généré automatiquement</p>
                </div>
                
                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700 mb-1">Points Initiaux</label>
                    <input type="number" name="points" id="points" min="0" value="<?php echo e(old('points', 0)); ?>"
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
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Date d'Expiration</label>
                    <input type="date" name="expires_at" id="expires_at" value="<?php echo e(old('expires_at')); ?>"
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
                        <option value="active" <?php echo e(old('status', 'active') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        <option value="suspended" <?php echo e(old('status') == 'suspended' ? 'selected' : ''); ?>>Suspendue</option>
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
            </div>
            
            <div class="mt-6 flex justify-end">
                <a href="<?php echo e(route('clients.show', $client)); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Annuler
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Créer la Carte
                </button>
            </div>
        </form>
    </div>
    
    <!-- Loyalty Program Information -->
    <div class="mt-6 bg-blue-50 rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Programme de Fidélité</h2>
        <div class="prose max-w-none">
            <p>Le programme de fidélité permet de récompenser les clients les plus fidèles avec des points qui peuvent être échangés contre des avantages.</p>
            <h3 class="text-md font-semibold mt-3">Niveaux de Fidélité :</h3>
            <ul class="list-disc pl-5 mt-2">
                <li><strong>Bronze</strong> : 0-499 points</li>
                <li><strong>Argent</strong> : 500-1999 points</li>
                <li><strong>Or</strong> : 2000-4999 points</li>
                <li><strong>Platine</strong> : 5000+ points</li>
            </ul>
            <p class="mt-3"><strong>Avantages :</strong> Les clients de niveaux supérieurs bénéficient de réductions, d'offres spéciales et de services prioritaires.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\loyalty\create.blade.php ENDPATH**/ ?>