

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h4 class="text-2xl font-bold mb-2">
                Fiche de Paie #<?php echo e($payslip->id); ?>

                <?php if($payslip->status == 'draft'): ?>
                    <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded ml-2 text-sm">Brouillon</span>
                <?php elseif($payslip->status == 'validated'): ?>
                    <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded ml-2 text-sm">Validé</span>
                <?php elseif($payslip->status == 'paid'): ?>
                    <span class="bg-green-200 text-green-800 px-2 py-1 rounded ml-2 text-sm">Payé</span>
                <?php endif; ?>
            </h4>
            <nav class="text-gray-500 text-sm">
                <ol class="list-reset flex">
                    <li><a href="<?php echo e(route('dashboard')); ?>" class="hover:underline">Tableau de bord</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="<?php echo e(route('hr.payslips.index')); ?>" class="hover:underline">Fiches de paie</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-700">#<?php echo e($payslip->id); ?></li>
                </ol>
            </nav>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
            <a href="<?php echo e(route('hr.payslips.download', $payslip)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center">
                <i class="mdi mdi-download mr-1"></i> Télécharger PDF
            </a>
            <?php if($payslip->status == 'draft'): ?>
                <a href="<?php echo e(route('hr.payslips.edit', $payslip)); ?>" class="bg-yellow-400 text-gray-800 px-4 py-2 rounded hover:bg-yellow-500 flex items-center">
                    <i class="mdi mdi-pencil mr-1"></i> Modifier
                </a>
                <form action="<?php echo e(route('hr.payslips.validate', $payslip)); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" onclick="return confirm('Valider cette fiche de paie ?')" class="bg-blue-400 text-gray-800 px-4 py-2 rounded hover:bg-blue-500 flex items-center">
                        <i class="mdi mdi-check mr-1"></i> Valider
                    </button>
                </form>
            <?php elseif($payslip->status == 'validated'): ?>
                <form action="<?php echo e(route('hr.payslips.pay', $payslip)); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" onclick="return confirm('Marquer comme payé ?')" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex items-center">
                        <i class="mdi mdi-cash mr-1"></i> Marquer Payé
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Payslip Content -->
    <div class="bg-white shadow-md rounded p-6 payslip-content">
        <!-- Header Infos -->
        <div class="flex flex-col md:flex-row justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-700"><?php echo e(config('app.name', 'TPT-H ERP')); ?></h3>
                <p class="text-gray-500 text-sm">
                    Adresse de l'entreprise<br>
                    Ville, Code Postal<br>
                    Téléphone: +XXX XXX XXX XXX
                </p>
            </div>
            <div class="text-right mt-4 md:mt-0">
                <h4 class="text-lg font-semibold">FICHE DE PAIE</h4>
                <p class="text-sm mb-1"><strong>Période:</strong> <?php echo e($payslip->period_start->format('d/m/Y')); ?> - <?php echo e($payslip->period_end->format('d/m/Y')); ?></p>
                <p class="text-sm mb-1"><strong>Date de génération:</strong> <?php echo e($payslip->created_at->format('d/m/Y')); ?></p>
                <p class="text-sm mb-1"><strong>Référence:</strong> #<?php echo e($payslip->reference); ?></p>
            </div>
        </div>

        <hr class="my-4">

        <!-- Employee & Company Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h5 class="font-semibold mb-2">Informations Employé</h5>
                <table class="w-full text-sm text-gray-700">
                    <tr><td class="font-medium">Nom complet:</td><td><?php echo e($payslip->employee->prenom); ?> <?php echo e($payslip->employee->nom); ?></td></tr>
                    <tr><td class="font-medium">Poste:</td><td><?php echo e($payslip->employee->currentPosition->title ?? 'N/A'); ?></td></tr>
                    <tr><td class="font-medium">Matricule:</td><td><?php echo e($payslip->employee->matricule); ?></td></tr>
                    <tr><td class="font-medium">Date d'embauche:</td><td><?php echo e($payslip->employee->date_embauche ? \Carbon\Carbon::parse($payslip->employee->date_embauche)->format('d/m/Y') : 'N/A'); ?></td></tr>
                </table>
            </div>
            <div>
                <h5 class="font-semibold mb-2">Informations Entreprise</h5>
                <table class="w-full text-sm text-gray-700">
                    <tr><td class="font-medium">Entreprise:</td><td><?php echo e($payslip->employee->currentCompany->raison_sociale ?? 'N/A'); ?></td></tr>
                    <tr><td class="font-medium">Agence:</td><td><?php echo e($payslip->employee->currentAgency->nom ?? 'N/A'); ?></td></tr>
                    <tr><td class="font-medium">Département:</td><td><?php echo e($payslip->employee->department->nom ?? 'N/A'); ?></td></tr>
                </table>
            </div>
        </div>

        <hr class="my-4">

        <!-- Salary Details -->
        <h5 class="font-semibold mb-2">Détail des Rémunérations</h5>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 text-left">Libellé</th>
                        <th class="border px-2 py-1 text-right">Montant (FCFA)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="border px-2 py-1 font-medium">Salaire de Base</td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->base_salary, 0, ',', ' ')); ?></td></tr>
                    <?php if($payslip->overtime_hours > 0): ?>
                    <tr><td class="border px-2 py-1">Heures Supplémentaires (<?php echo e($payslip->overtime_hours); ?>h × <?php echo e(number_format($payslip->overtime_rate,0,',',' ')); ?>)</td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->overtime_hours * $payslip->overtime_rate, 0, ',', ' ')); ?></td></tr>
                    <?php endif; ?>
                    <?php if($payslip->transport_allowance > 0): ?>
                    <tr><td class="border px-2 py-1">Allocation Transport</td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->transport_allowance, 0, ',', ' ')); ?></td></tr>
                    <?php endif; ?>
                    <?php if($payslip->housing_allowance > 0): ?>
                    <tr><td class="border px-2 py-1">Allocation Logement</td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->housing_allowance, 0, ',', ' ')); ?></td></tr>
                    <?php endif; ?>
                    <?php if($payslip->meal_allowance > 0): ?>
                    <tr><td class="border px-2 py-1">Allocation Repas</td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->meal_allowance, 0, ',', ' ')); ?></td></tr>
                    <?php endif; ?>
                    <?php if($payslip->performance_bonus > 0): ?>
                    <tr><td class="border px-2 py-1">Prime de Performance</td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->performance_bonus, 0, ',', ' ')); ?></td></tr>
                    <?php endif; ?>
                    <?php if($payslip->other_allowances > 0): ?>
                    <tr><td class="border px-2 py-1"><?php echo e($payslip->allowances_description ?: 'Autres Allocations'); ?></td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->other_allowances, 0, ',', ' ')); ?></td></tr>
                    <?php endif; ?>
                    <tr class="bg-gray-100 font-semibold"><td class="border px-2 py-1">TOTAL BRUT</td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->gross_salary,0,',',' ')); ?></td></tr>
                </tbody>
            </table>
        </div>

        <!-- Deductions -->
        <h5 class="font-semibold mt-6 mb-2">Déductions</h5>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 text-left">Libellé</th>
                        <th class="border px-2 py-1 text-right">Montant (FCFA)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($payslip->social_security > 0): ?>
                    <tr><td class="border px-2 py-1">Cotisation Sécurité Sociale</td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->social_security,0,',',' ')); ?></td></tr>
                    <?php endif; ?>
                    <?php if($payslip->income_tax > 0): ?>
                    <tr><td class="border px-2 py-1">Impôt sur le Revenu</td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->income_tax,0,',',' ')); ?></td></tr>
                    <?php endif; ?>
                    <?php if($payslip->advance_deduction > 0): ?>
                    <tr><td class="border px-2 py-1">Avance sur Salaire</td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->advance_deduction,0,',',' ')); ?></td></tr>
                    <?php endif; ?>
                    <?php if($payslip->loan_deduction > 0): ?>
                    <tr><td class="border px-2 py-1">Remboursement Prêt</td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->loan_deduction,0,',',' ')); ?></td></tr>
                    <?php endif; ?>
                    <?php if($payslip->other_deductions > 0): ?>
                    <tr><td class="border px-2 py-1"><?php echo e($payslip->deductions_description ?: 'Autres Déductions'); ?></td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->other_deductions,0,',',' ')); ?></td></tr>
                    <?php endif; ?>
                    <tr class="bg-yellow-100 font-semibold"><td class="border px-2 py-1">TOTAL DÉDUCTIONS</td><td class="border px-2 py-1 text-right"><?php echo e(number_format($payslip->total_deductions,0,',',' ')); ?></td></tr>
                </tbody>
            </table>
        </div>

        <!-- Net Salary -->
        <div class="bg-green-100 text-green-800 font-bold text-center py-4 mt-6 rounded">
            <h4>SALAIRE NET À PAYER</h4>
            <p class="text-2xl"><?php echo e(number_format($payslip->net_salary,0,',',' ')); ?> FCFA</p>
        </div>

        <!-- Notes -->
        <?php if($payslip->notes): ?>
        <div class="mt-6">
            <h6 class="font-semibold mb-2">Notes/Commentaires</h6>
            <div class="bg-gray-100 text-gray-700 p-3 rounded"><?php echo e($payslip->notes); ?></div>
        </div>
        <?php endif; ?>

        <!-- Signatures -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div>
                <p class="mb-2">Signature de l'Employé:</p>
                <div class="border-b-2 border-gray-700 h-12 w-48 mb-1"></div>
                <small><?php echo e($payslip->employee->prenom); ?> <?php echo e($payslip->employee->nom); ?></small>
            </div>
            <div class="text-right">
                <p class="mb-2">Signature de l'Employeur:</p>
                <div class="border-b-2 border-gray-700 h-12 w-48 mx-auto md:ml-auto mb-1"></div>
                <small>Direction des Ressources Humaines</small>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .btn, .breadcrumb { display: none !important; }
    body { font-size: 12px; }
    .payslip-content { box-shadow: none !important; }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\payslips\show.blade.php ENDPATH**/ ?>