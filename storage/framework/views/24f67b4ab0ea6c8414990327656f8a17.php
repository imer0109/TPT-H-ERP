

<?php $__env->startSection('title', 'Détail de la livraison'); ?>

<?php $__env->startSection('header', 'Détail de la livraison'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Livraison #<?php echo e($delivery->code); ?></h2>
            <span class="inline-flex rounded-full bg-<?php echo e($delivery->statut === 'completed' ? 'green' : ($delivery->statut === 'pending' ? 'yellow' : 'red')); ?>-100 px-3 py-1 text-sm font-semibold leading-5 text-<?php echo e($delivery->statut === 'completed' ? 'green' : ($delivery->statut === 'pending' ? 'yellow' : 'red')); ?>-800">
                <?php echo e(ucfirst($delivery->statut)); ?>

            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informations de la livraison</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Date de livraison:</span> <?php echo e($delivery->date_livraison->format('d/m/Y')); ?></p>
                    <p><span class="font-medium">Code:</span> <?php echo e($delivery->code); ?></p>
                    <p><span class="font-medium">Commande:</span> <?php echo e($delivery->order?->code ?? 'N/A'); ?></p>
                    <p><span class="font-medium">Fournisseur:</span> <?php echo e($delivery->fournisseur?->raison_sociale ?? 'N/A'); ?></p>
                    <p><span class="font-medium">Entrepôt:</span> <?php echo e($delivery->warehouse?->nom ?? 'N/A'); ?></p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Détails supplémentaires</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Statut:</span> <?php echo e(ucfirst($delivery->statut)); ?></p>
                    <p><span class="font-medium">Quantité totale:</span> <?php echo e($delivery->items->sum('quantite')); ?></p>
                    <p><span class="font-medium">Date de création:</span> <?php echo e($delivery->created_at->format('d/m/Y H:i')); ?></p>
                    <?php if($delivery->notes): ?>
                        <p><span class="font-medium">Notes:</span> <?php echo e($delivery->notes); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <h3 class="text-lg font-semibold text-gray-700 mb-3">Articles livrés</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Prix unitaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $delivery->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($item->product?->libelle ?? 'N/A'); ?></td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($item->quantite); ?></td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e(number_format($item->prix_unitaire, 0, ',', ' ')); ?> FCFA</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e(number_format($item->total, 0, ',', ' ')); ?> FCFA</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Aucun article trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($delivery->order): ?>
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Commande associée</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><span class="font-medium">Code commande:</span> <a href="<?php echo e(route('supplier.portal.orders.show', $delivery->order)); ?>" class="text-blue-600 hover:text-blue-800"><?php echo e($delivery->order->code); ?></a></p>
                            <p><span class="font-medium">Date commande:</span> <?php echo e($delivery->order->date_commande->format('d/m/Y')); ?></p>
                        </div>
                        <div>
                            <p><span class="font-medium">Statut commande:</span> <?php echo e(ucfirst($delivery->order->statut)); ?></p>
                            <p><span class="font-medium">Montant TTC:</span> <?php echo e(number_format($delivery->order->montant_ttc, 0, ',', ' ')); ?> FCFA</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="mt-6 flex justify-end">
            <a href="<?php echo e(route('supplier.portal.deliveries')); ?>" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\portal\deliveries\show.blade.php ENDPATH**/ ?>