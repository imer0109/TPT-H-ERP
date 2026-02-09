

<?php $__env->startSection('title', 'Détail de la commande'); ?>

<?php $__env->startSection('header', 'Détail de la commande'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Commande #<?php echo e($order->code); ?></h2>
            <span class="inline-flex rounded-full bg-<?php echo e($order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : ($order->statut === 'approved' ? 'blue' : 'red'))); ?>-100 px-3 py-1 text-sm font-semibold leading-5 text-<?php echo e($order->statut === 'delivered' ? 'green' : ($order->statut === 'pending' ? 'yellow' : ($order->statut === 'approved' ? 'blue' : 'red'))); ?>-800">
                <?php echo e(ucfirst($order->statut)); ?>

            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informations de la commande</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Date:</span> <?php echo e($order->date_commande->format('d/m/Y')); ?></p>
                    <p><span class="font-medium">Code:</span> <?php echo e($order->code); ?></p>
                    <p><span class="font-medium">Agence:</span> <?php echo e($order->agency?->nom ?? 'N/A'); ?></p>
                    <p><span class="font-medium">Fournisseur:</span> <?php echo e($order->fournisseur?->raison_sociale ?? 'N/A'); ?></p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Montants</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Montant HT:</span> <?php echo e(number_format($order->montant_ht, 0, ',', ' ')); ?> FCFA</p>
                    <p><span class="font-medium">TVA:</span> <?php echo e(number_format($order->tva, 0, ',', ' ')); ?> FCFA</p>
                    <p><span class="font-medium">Montant TTC:</span> <?php echo e(number_format($order->montant_ttc, 0, ',', ' ')); ?> FCFA</p>
                    <p><span class="font-medium">Montant payé:</span> <?php echo e(number_format($order->invoices->sum(function($invoice) { return $invoice->payments->sum('montant'); }), 0, ',', ' ')); ?> FCFA</p>
                    <p><span class="font-medium">Montant restant:</span> <?php echo e(number_format($order->montant_ttc - $order->invoices->sum(function($invoice) { return $invoice->payments->sum('montant'); }), 0, ',', ' ')); ?> FCFA</p>
                </div>
            </div>
        </div>
        
        <h3 class="text-lg font-semibold text-gray-700 mb-3">Articles de la commande</h3>
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
                    <?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
        
        <?php if($order->deliveries && $order->deliveries->count() > 0): ?>
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Livraisons associées</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Quantité livrée</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <?php $__currentLoopData = $order->deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($delivery->code); ?></td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($delivery->date_livraison->format('d/m/Y')); ?></td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span class="inline-flex rounded-full bg-<?php echo e($delivery->statut === 'completed' ? 'green' : ($delivery->statut === 'pending' ? 'yellow' : 'red')); ?>-100 px-2 text-xs font-semibold leading-5 text-<?php echo e($delivery->statut === 'completed' ? 'green' : ($delivery->statut === 'pending' ? 'yellow' : 'red')); ?>-800">
                                            <?php echo e(ucfirst($delivery->statut)); ?>

                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($delivery->items->sum('quantite')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if($order->invoices && $order->invoices->count() > 0): ?>
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Factures associées</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Numéro</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <?php $__currentLoopData = $order->invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($invoice->numero_facture); ?></td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e($invoice->date_facture->format('d/m/Y')); ?></td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span class="inline-flex rounded-full bg-<?php echo e($invoice->statut === 'paid' ? 'green' : ($invoice->statut === 'pending' ? 'yellow' : 'red')); ?>-100 px-2 text-xs font-semibold leading-5 text-<?php echo e($invoice->statut === 'paid' ? 'green' : ($invoice->statut === 'pending' ? 'yellow' : 'red')); ?>-800">
                                            <?php echo e(ucfirst($invoice->statut)); ?>

                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"><?php echo e(number_format($invoice->montant, 0, ',', ' ')); ?> FCFA</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="mt-6 flex justify-end">
            <a href="<?php echo e(route('supplier.portal.orders')); ?>" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\portal\orders\show.blade.php ENDPATH**/ ?>