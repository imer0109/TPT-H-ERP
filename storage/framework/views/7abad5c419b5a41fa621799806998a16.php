

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Rapport de Session de Caisse</h1>
            <div class="flex space-x-2">
                <a href="<?php echo e(route('cash.registers.show', ['cashRegister' => $session->cashRegister->id])); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                    Retour à la caisse
                </a>
                <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded print:hidden">
                    Imprimer
                </button>
            </div>
        </div>

        <!-- Informations de la session -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6"> 
                <h3 class="text-lg leading-6 font-medium text-gray-900">Informations de la session</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Détails de la session de caisse
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Caisse</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($session->cashRegister->nom); ?></dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Entité</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php if($session->cashRegister->entity): ?>
                                <?php echo e(class_basename($session->cashRegister->entity_type) === 'Societe' ? 'Société' : 'Agence'); ?> : <?php echo e($session->cashRegister->entity->nom); ?>

                            <?php else: ?>
                                Non assignée
                            <?php endif; ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Caissier</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($session->user->name); ?></dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Date d'ouverture</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($session->date_ouverture->format('d/m/Y H:i')); ?></dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Date de fermeture</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php echo e($session->date_fermeture ? $session->date_fermeture->format('d/m/Y H:i') : 'Session en cours'); ?>

                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Solde initial</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e(number_format($session->solde_initial, 2, ',', ' ')); ?> FCFA</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Solde final</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php echo e($session->solde_final ? number_format($session->solde_final, 2, ',', ' ') . ' FCFA' : 'Non défini'); ?>

                        </dd>
                    </div>
                    <?php if($session->commentaire): ?>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Commentaire</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($session->commentaire); ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($session->justificatif_fermeture): ?>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Justificatif de fermeture</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <a href="<?php echo e(Storage::url($session->justificatif_fermeture)); ?>" target="_blank" class="text-indigo-600 hover:text-indigo-900">Voir le document</a>
                        </dd>
                    </div>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <!-- Résumé financier -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Résumé financier</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500">Solde initial</p>
                        <p class="text-xl font-bold text-gray-900"><?php echo e(number_format($session->solde_initial, 2, ',', ' ')); ?> FCFA</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-green-800">Total encaissements</p>
                        <p class="text-xl font-bold text-green-600"><?php echo e(number_format($encaissements->sum('montant'), 2, ',', ' ')); ?> FCFA</p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-red-800">Total décaissements</p>
                        <p class="text-xl font-bold text-red-600"><?php echo e(number_format($decaissements->sum('montant'), 2, ',', ' ')); ?> FCFA</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-blue-800">Solde calculé</p>
                        <?php
                            $soldeCalcule = $session->solde_initial + $encaissements->sum('montant') - $decaissements->sum('montant');
                            $difference = $session->solde_final ? abs($soldeCalcule - $session->solde_final) : 0;
                        ?>
                        <p class="text-xl font-bold text-blue-600"><?php echo e(number_format($soldeCalcule, 2, ',', ' ')); ?> FCFA</p>
                    </div>
                </div>

                <?php if($session->solde_final && $difference > 0.01): ?>
                <div class="mt-4 bg-yellow-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-yellow-800">Écart constaté</p>
                    <p class="text-xl font-bold text-yellow-600"><?php echo e(number_format($difference, 2, ',', ' ')); ?> FCFA</p>
                    <p class="text-xs text-yellow-700 mt-1">Différence entre le solde calculé et le solde final déclaré</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Détail des transactions -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Détail des encaissements</h3>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Transaction</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Libellé</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mode</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $encaissements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($transaction->numero_transaction); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e(number_format($transaction->montant, 2, ',', ' ')); ?> FCFA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($transaction->libelle); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($transaction->mode_paiement); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($transaction->user->name); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Aucun encaissement pour cette session</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Détail des décaissements</h3>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Transaction</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Libellé</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mode</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $decaissements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($transaction->numero_transaction); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e(number_format($transaction->montant, 2, ',', ' ')); ?> FCFA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($transaction->libelle); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($transaction->mode_paiement); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($transaction->user->name); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Aucun décaissement pour cette session</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Signature -->
        <?php if($session->date_fermeture): ?>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6 print:block hidden">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Signatures</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-16">Le caissier</p>
                        <p class="text-sm text-gray-900"><?php echo e($session->user->name); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-16">Le responsable</p>
                        <p class="text-sm text-gray-900">................................................</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    @media print {
        body {
            font-size: 12pt;
        }
        .container {
            max-width: 100%;
            padding: 0;
        }
        .print\:hidden {
            display: none !important;
        }
        .print\:block {
            display: block !important;
        }
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\cash\sessions\report.blade.php ENDPATH**/ ?>