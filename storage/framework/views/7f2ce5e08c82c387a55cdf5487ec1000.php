<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Livraison <?php if($delivery->order): ?> <?php echo e($delivery->order->code); ?> <?php endif; ?>
                    </h2>
                    <div class="flex space-x-2">
                        <?php if($delivery->statut == 'received' || $delivery->statut == 'partial'): ?>
                            <a href="<?php echo e(route('purchases.deliveries.edit', $delivery)); ?>" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Modifier
                            </a>
                            
                            <?php if(!$delivery->isServiceDelivery()): ?>
                                <form action="<?php echo e(route('purchases.deliveries.validate', $delivery)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" 
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Êtes-vous sûr de vouloir valider cette livraison ?')">
                                        Valider
                                    </button>
                                </form>
                                
                                <form action="<?php echo e(route('purchases.deliveries.validate', $delivery)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Êtes-vous sûr de vouloir rejeter cette livraison ?')">
                                        Rejeter
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <a href="<?php echo e(route('purchases.deliveries.index')); ?>" 
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Retour
                        </a>
                    </div>
                </div>

                <?php if(session('success')): ?>
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 p-4 rounded">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Informations générales</h3>
                        
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Bon de commande:</span>
                                <div class="text-sm text-gray-900">
                                    <?php if($delivery->order): ?>
                                        <a href="<?php echo e(route('purchases.orders.show', $delivery->order)); ?>" class="text-red-600 hover:text-red-900">
                                            <?php echo e($delivery->order->code); ?>

                                        </a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Fournisseur:</span>
                                <div class="text-sm text-gray-900"><?php echo e($delivery->fournisseur->raison_sociale ?? 'N/A'); ?></div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Entrepôt:</span>
                                <div class="text-sm text-gray-900"><?php echo e($delivery->warehouse->nom ?? 'N/A'); ?></div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Numéro BL:</span>
                                <div class="text-sm text-gray-900"><?php echo e($delivery->numero_bl); ?></div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Date de réception:</span>
                                <div class="text-sm text-gray-900"><?php echo e($delivery->date_reception->format('d/m/Y')); ?></div>
                            </div>
                            
                            <?php if($delivery->condition_emballage): ?>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Condition de l'emballage:</span>
                                    <div class="text-sm text-gray-900"><?php echo e(ucfirst($delivery->condition_emballage)); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Statut et validation</h3>
                        
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Statut:</span>
                                <div class="text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($delivery->getFormattedStatus()['color']); ?>">
                                        <?php echo e($delivery->getFormattedStatus()['text']); ?>

                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Reçue par:</span>
                                <div class="text-sm text-gray-900">
                                    <?php echo e($delivery->receivedBy->name ?? 'N/A'); ?>

                                    <?php if($delivery->created_at): ?>
                                        le <?php echo e($delivery->created_at->format('d/m/Y H:i')); ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if($delivery->validated_by): ?>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Validée par:</span>
                                    <div class="text-sm text-gray-900">
                                        <?php echo e($delivery->validatedBy->name ?? 'N/A'); ?>

                                        <?php if($delivery->validated_at): ?>
                                            le <?php echo e($delivery->validated_at->format('d/m/Y H:i')); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($delivery->validation_notes): ?>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Notes de validation:</span>
                                    <div class="text-sm text-gray-900"><?php echo e($delivery->validation_notes); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if($delivery->notes): ?>
                    <div class="mb-6 bg-gray-50 p-4 rounded">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Notes</h3>
                        <div class="text-sm text-gray-900"><?php echo e($delivery->notes); ?></div>
                    </div>
                <?php endif; ?>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <?php if($delivery->isServiceDelivery()): ?>
                            Détails du service livré
                        <?php else: ?>
                            Articles livrés
                        <?php endif; ?>
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <?php if(!$delivery->isServiceDelivery()): ?>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Désignation</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité commandée</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité livrée</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Écart</th>
                                    <?php else: ?>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Satisfaction</th>
                                    <?php endif; ?>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $delivery->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <?php if(!$delivery->isServiceDelivery()): ?>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo e($item->product->name ?? $item->orderItem->designation ?? 'N/A'); ?>

                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-sm text-gray-900"><?php echo e($item->quantite_commandee); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-sm text-gray-900"><?php echo e($item->quantite_livree); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <?php if($item->hasDiscrepancy()): ?>
                                                    <span class="text-sm font-medium <?php echo e($item->ecart > 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                                        <?php echo e($item->getDiscrepancyDescription()); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-sm text-gray-900">Conforme</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php else: ?>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">Service rendu</div>
                                                <?php if($item->compte_rendu): ?>
                                                    <div class="text-sm text-gray-500 mt-1">Compte rendu: <?php echo e($item->compte_rendu); ?></div>
                                                <?php endif; ?>
                                                <?php if($item->preuve_service): ?>
                                                    <div class="text-sm text-gray-500 mt-1">Preuve: <?php echo e($item->preuve_service); ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <?php if($item->satisfaction): ?>
                                                    <div class="flex items-center justify-center">
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <svg class="<?php echo e($i <= $item->satisfaction ? 'text-yellow-400' : 'text-gray-300'); ?> h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        <?php endfor; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900"><?php echo e($item->notes ?? '-'); ?></div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="<?php echo e($delivery->isServiceDelivery() ? 3 : 5); ?>" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Aucun article trouvé.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\purchases\deliveries\show.blade.php ENDPATH**/ ?>