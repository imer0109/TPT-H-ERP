

<?php $__env->startSection('content'); ?>
<div class="w-full px-4 py-6">
    <div class="bg-white shadow-md rounded-xl p-6">
        
        
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">
                Détails du Transfert #<?php echo e($transfer->numero_transfert); ?>

            </h3>

            <a href="<?php echo e(route('stock.transfers.index')); ?>" 
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                ← Retour
            </a>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            
            <div class="border rounded-lg overflow-hidden">
                <table class="w-full">
                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Statut</th>
                        <td class="p-3">
                            <span class="
                                px-3 py-1 rounded-full text-sm font-semibold
                                <?php if($transfer->statut === 'en_attente'): ?> bg-yellow-200 text-yellow-800 
                                <?php elseif($transfer->statut === 'en_transit'): ?> bg-blue-200 text-blue-800
                                <?php elseif($transfer->statut === 'receptionne'): ?> bg-green-200 text-green-800
                                <?php else: ?> bg-red-200 text-red-800
                                <?php endif; ?>
                            ">
                                <?php echo e(ucfirst(str_replace('_', ' ', $transfer->statut))); ?>

                            </span>
                        </td>
                    </tr>

                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Dépôt Source</th>
                        <td class="p-3"><?php echo e($transfer->warehouseSource->nom); ?></td>
                    </tr>

                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Dépôt Destination</th>
                        <td class="p-3"><?php echo e($transfer->warehouseDestination->nom); ?></td>
                    </tr>

                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Produit</th>
                        <td class="p-3"><?php echo e($transfer->product->name); ?></td>
                    </tr>

                    <tr>
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Quantité</th>
                        <td class="p-3"><?php echo e(intval($transfer->quantite)); ?> <?php echo e($transfer->unite); ?></td>
                    </tr>
                </table>
            </div>

            
            <div class="border rounded-lg overflow-hidden">
                <table class="w-full">
                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Créé par</th>
                        <td class="p-3">
                            <?php echo e($transfer->createdBy->name); ?>  
                            <span class="text-gray-500 text-sm">
                                (<?php echo e($transfer->created_at->format('d/m/Y H:i')); ?>)
                            </span>
                        </td>
                    </tr>

                    <?php if($transfer->validated_by): ?>
                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Validé par</th>
                        <td class="p-3">
                            <?php echo e($transfer->validatedBy->name); ?>  
                            <span class="text-gray-500 text-sm">
                                (<?php echo e($transfer->date_validation->format('d/m/Y H:i')); ?>)
                            </span>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if($transfer->received_by): ?>
                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Réceptionné par</th>
                        <td class="p-3">
                            <?php echo e($transfer->receivedBy->name); ?>  
                            <span class="text-gray-500 text-sm">
                                (<?php echo e($transfer->date_reception->format('d/m/Y H:i')); ?>)
                            </span>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if($transfer->justificatif): ?>
                    <tr class="border-b">
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Justificatif</th>
                        <td class="p-3">
                            <a href="<?php echo e(Storage::url($transfer->justificatif)); ?>" 
                               target="_blank" 
                               class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                                Voir le document
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if($transfer->notes): ?>
                    <tr>
                        <th class="bg-gray-100 p-3 text-left font-medium text-gray-700">Notes</th>
                        <td class="p-3"><?php echo e($transfer->notes); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        
        <div class="text-center mt-8 space-x-3">
            <?php if($transfer->statut === 'en_attente'): ?>
            <form action="<?php echo e(route('stock.transfers.validate', $transfer)); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                    class="px-5 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                    Valider le transfert
                </button>
            </form>
            <?php endif; ?>

            <?php if($transfer->statut === 'en_transit'): ?>
            <form action="<?php echo e(route('stock.transfers.receive', $transfer)); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                    class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Réceptionner
                </button>
            </form>
            <?php endif; ?>

            <?php if(in_array($transfer->statut, ['en_attente', 'en_transit'])): ?>
            <form action="<?php echo e(route('stock.transfers.cancel', $transfer)); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                    onclick="return confirm('Êtes-vous sûr de vouloir annuler ce transfert ?')"
                    class="px-5 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                    Annuler le transfert
                </button>
            </form>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\transfers\show.blade.php ENDPATH**/ ?>