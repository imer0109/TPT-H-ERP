

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Paiements fournisseurs</h1>
        <a href="<?php echo e(route('fournisseurs.payments.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Nouveau paiement</a>
    </div>

    <?php if($overdueInvoices->count() > 0): ?>
        <div class="bg-red-50 border border-red-200 rounded p-4 mb-6">
            <h3 class="text-lg font-medium text-red-800 mb-2">⚠️ Factures en retard</h3>
            <div class="space-y-2">
                <?php $__currentLoopData = $overdueInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex justify-between items-center bg-white p-3 rounded border">
                        <div>
                            <span class="font-medium"><?php echo e($invoice->numero_facture); ?></span>
                            <span class="text-gray-600">- <?php echo e($invoice->fournisseur->raison_sociale); ?></span>
                            <span class="text-sm text-gray-500">(Échéance: <?php echo e($invoice->date_echeance->format('d/m/Y')); ?>)</span>
                        </div>
                        <div class="text-right">
                            <div class="font-medium text-red-600"><?php echo e(number_format($invoice->solde, 0, ',', ' ')); ?> <?php echo e($invoice->devise); ?></div>
                            <div class="text-sm text-gray-500">Retard de <?php echo e($invoice->date_echeance->diffInDays(now())); ?> jours</div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fournisseur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($payment->date_paiement->format('d/m/Y')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($payment->fournisseur->raison_sociale); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php if($payment->invoice): ?>
                                    <span class="text-blue-600"><?php echo e($payment->invoice->numero_facture); ?></span>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e(number_format($payment->montant, 0, ',', ' ')); ?> <?php echo e($payment->devise); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">
                                    <?php echo e(ucfirst($payment->mode_paiement)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($payment->reference_paiement ?? '-'); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucun paiement enregistré</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-3 border-t border-gray-200">
            <?php echo e($payments->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\payments\index.blade.php ENDPATH**/ ?>