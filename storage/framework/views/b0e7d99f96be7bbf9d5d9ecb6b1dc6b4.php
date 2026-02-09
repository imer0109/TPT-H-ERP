

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Transactions du Client: <?php echo e($client->nom_raison_sociale); ?></h1>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('clients.show', $client)); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la fiche client
            </a>
            <a href="<?php echo e(route('clients.transactions.export', $client)); ?>" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-file-export mr-2"></i> Exporter (CSV)
            </a>
        </div>
    </div>

    <!-- Résumé financier -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-arrow-down fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Encaissements</p>
                    <p class="text-2xl font-bold text-green-600"><?php echo e(number_format($totalEncaissements, 0, ',', ' ')); ?> FCFA</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                    <i class="fas fa-arrow-up fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Décaissements</p>
                    <p class="text-2xl font-bold text-red-600"><?php echo e(number_format($totalDecaissements, 0, ',', ' ')); ?> FCFA</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 md:col-span-2">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-balance-scale fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Solde Actuel</p>
                    <p class="text-2xl font-bold <?php echo e($solde >= 0 ? 'text-green-600' : 'text-red-600'); ?>"><?php echo e(number_format($solde, 0, ',', ' ')); ?> FCFA</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="<?php echo e(route('clients.transactions', $client)); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" id="type" 
                    class="w-full rounded-md border-2 border-gray-400 bg-white text-gray-900 px-3 py-2 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-200">
                    <option value="">Tous les types</option>
                    <option value="encaissement" <?php echo e(request('type') == 'encaissement' ? 'selected' : ''); ?>>Encaissement</option>
                    <option value="decaissement" <?php echo e(request('type') == 'decaissement' ? 'selected' : ''); ?>>Décaissement</option>
                </select>
            </div>

            <div>
                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                <input type="date" name="date_debut" id="date_debut" value="<?php echo e(request('date_debut')); ?>" 
                    class="w-full rounded-md border-2 border-gray-400 bg-white text-gray-900 px-3 py-2 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-200">
            </div>

            <div>
                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                <input type="date" name="date_fin" id="date_fin" value="<?php echo e(request('date_fin')); ?>" 
                    class="w-full rounded-md border-2 border-gray-400 bg-white text-gray-900 px-3 py-2 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-200">
            </div>

            <div class="md:col-span-4 flex justify-end space-x-2">
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
                <a href="<?php echo e(route('clients.transactions', $client)); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-times mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des transactions -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Libellé</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caisse</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php echo e($transaction->type == 'encaissement' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                            <?php echo e(ucfirst($transaction->type)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo e($transaction->numero_transaction); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($transaction->libelle); ?></td>
                    <td class="px-6 py-4 text-sm font-semibold <?php echo e($transaction->type == 'encaissement' ? 'text-green-600' : 'text-red-600'); ?>">
                        <?php echo e($transaction->type == 'encaissement' ? '+' : '-'); ?> <?php echo e(number_format($transaction->montant, 0, ',', ' ')); ?> FCFA
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($transaction->cashRegister->nom ?? 'N/A'); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($transaction->user->nom ?? 'N/A'); ?> <?php echo e($transaction->user->prenom ?? ''); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-sm text-gray-500 text-center">Aucune transaction trouvée</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="px-6 py-4">
            <?php echo e($transactions->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\transactions.blade.php ENDPATH**/ ?>