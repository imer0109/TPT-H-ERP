

<?php $__env->startSection('title', 'Détail du paiement'); ?>

<?php $__env->startSection('header', 'Détail du paiement'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Paiement #<?php echo e($payment->id); ?></h2>
            <span class="inline-flex rounded-full bg-<?php echo e($payment->statut === 'validated' ? 'green' : ($payment->statut === 'pending' ? 'yellow' : 'red')); ?>-100 px-3 py-1 text-sm font-semibold leading-5 text-<?php echo e($payment->statut === 'validated' ? 'green' : ($payment->statut === 'pending' ? 'yellow' : 'red')); ?>-800">
                <?php echo e(ucfirst($payment->statut)); ?>

            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informations du paiement</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Date:</span> <?php echo e($payment->date_paiement->format('d/m/Y')); ?></p>
                    <p><span class="font-medium">Mode de paiement:</span> <?php echo e(ucfirst($payment->mode_paiement)); ?></p>
                    <p><span class="font-medium">Montant:</span> <?php echo e(number_format($payment->montant, 0, ',', ' ')); ?> FCFA</p>
                    <p><span class="font-medium">Fournisseur:</span> <?php echo e($payment->fournisseur?->raison_sociale ?? 'N/A'); ?></p>
                    <p><span class="font-medium">Validé par:</span> <?php echo e($payment->validatedBy?->name ?? 'N/A'); ?></p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Détails du paiement</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Statut:</span> <?php echo e(ucfirst($payment->statut)); ?></p>
                    <p><span class="font-medium">Date de création:</span> <?php echo e($payment->created_at->format('d/m/Y H:i')); ?></p>
                    <?php if($payment->reference): ?>
                        <p><span class="font-medium">Référence:</span> <?php echo e($payment->reference); ?></p>
                    <?php endif; ?>
                    <?php if($payment->notes): ?>
                        <p><span class="font-medium">Notes:</span> <?php echo e($payment->notes); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if($payment->invoice): ?>
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Facture associée</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><span class="font-medium">Numéro facture:</span> <a href="<?php echo e(route('supplier.portal.invoices.show', $payment->invoice)); ?>" class="text-blue-600 hover:text-blue-800"><?php echo e($payment->invoice->numero_facture); ?></a></p>
                            <p><span class="font-medium">Date facture:</span> <?php echo e($payment->invoice->date_facture->format('d/m/Y')); ?></p>
                        </div>
                        <div>
                            <p><span class="font-medium">Statut facture:</span> <?php echo e(ucfirst($payment->invoice->statut)); ?></p>
                            <p><span class="font-medium">Montant facture:</span> <?php echo e(number_format($payment->invoice->montant_ttc, 0, ',', ' ')); ?> FCFA</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if($payment->invoice && $payment->invoice->order): ?>
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Commande associée</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><span class="font-medium">Code commande:</span> <a href="<?php echo e(route('supplier.portal.orders.show', $payment->invoice->order)); ?>" class="text-blue-600 hover:text-blue-800"><?php echo e($payment->invoice->order->code); ?></a></p>
                            <p><span class="font-medium">Date commande:</span> <?php echo e($payment->invoice->order->date_commande->format('d/m/Y')); ?></p>
                        </div>
                        <div>
                            <p><span class="font-medium">Statut commande:</span> <?php echo e(ucfirst($payment->invoice->order->statut)); ?></p>
                            <p><span class="font-medium">Montant commande:</span> <?php echo e(number_format($payment->invoice->order->montant_ttc, 0, ',', ' ')); ?> FCFA</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="mt-6 flex justify-end">
            <a href="<?php echo e(route('supplier.portal.payments')); ?>" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\portal\payments\show.blade.php ENDPATH**/ ?>