

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-exchange-alt mr-2"></i> Transferts de Stock
            </h2>
            <a href="<?php echo e(route('stock.transfers.create')); ?>" 
               class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 shadow">
                <i class="fas fa-plus mr-1"></i> Nouveau Transfert
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-gray-600 font-semibold">N° Transfert</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Date</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Dépôt Source</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Dépôt Destination</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Produit</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Unité</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Quantité</th>
                        <th class="px-6 py-3 text-gray-600 font-semibold">Statut</th>
                        <th class="px-6 py-3 text-center text-gray-600 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $transfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">#<?php echo e($transfer->numero_transfert); ?></td>
                        <td class="px-6 py-4"><?php echo e($transfer->created_at->format('d/m/Y H:i')); ?></td>
                        <td class="px-6 py-4"><?php echo e($transfer->warehouseSource->nom); ?></td>
                        <td class="px-6 py-4"><?php echo e($transfer->warehouseDestination->nom); ?></td>
                        <td class="px-6 py-4"><?php echo e($transfer->product->name); ?></td>
                        <td class="px-6 py-4"><?php echo e($transfer->unite); ?></td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-sm">
                                <?php echo e(number_format($transfer->quantite, 0, ',', ' ')); ?> 
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                <?php echo e($transfer->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-700' : ''); ?>

                                <?php echo e($transfer->statut === 'en_transit' ? 'bg-blue-100 text-blue-700' : ''); ?>

                                <?php echo e($transfer->statut === 'receptionne' ? 'bg-green-100 text-green-700' : ''); ?>

                                <?php echo e($transfer->statut === 'annule' ? 'bg-red-100 text-red-700' : ''); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $transfer->statut))); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <!-- Voir -->
                            <a href="<?php echo e(route('stock.transfers.show', $transfer)); ?>" 
                               class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200" 
                               title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Valider -->
                            <?php if($transfer->statut === 'en_attente'): ?>
                            <form action="<?php echo e(route('stock.transfers.validate', $transfer)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 hover:bg-green-200"
                                    title="Valider transfert">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <?php endif; ?>

                            <!-- Réception -->
                            <?php if($transfer->statut === 'en_transit'): ?>
                            <form action="<?php echo e(route('stock.transfers.receive', $transfer)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 hover:bg-indigo-200"
                                    title="Marquer comme reçu">
                                    <i class="fas fa-inbox"></i>
                                </button>
                            </form>
                            <?php endif; ?>

                            <!-- Annuler -->
                            <?php if(in_array($transfer->statut, ['en_attente', 'en_transit'])): ?>
                            <form action="<?php echo e(route('stock.transfers.cancel', $transfer)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir annuler ce transfert ?')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200"
                                    title="Annuler transfert">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Aucun transfert trouvé.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t bg-gray-50">
            <?php echo e($transfers->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\transfers\index.blade.php ENDPATH**/ ?>