

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-semibold mb-6">Modifier Fiche de Paie #<?php echo e($payslip->id); ?></h2>

    <form action="<?php echo e(route('hr.payslips.update', $payslip)); ?>" method="POST" id="payslipForm" class="space-y-6 bg-white p-6 rounded-lg shadow">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Employé et Période -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-medium">Employé</label>
                <input type="text" value="<?php echo e($payslip->employee->prenom); ?> <?php echo e($payslip->employee->nom); ?> - <?php echo e($payslip->employee->currentPosition->title ?? 'N/A'); ?>" disabled class="w-full border rounded p-2 bg-gray-100">
                <input type="hidden" name="employee_id" value="<?php echo e($payslip->employee->id); ?>">
            </div>
            <div>
                <label class="block font-medium">Période</label>
                <input type="text" value="<?php echo e(DateTime::createFromFormat('!m', $payslip->month)->format('F')); ?> <?php echo e($payslip->year); ?>" disabled class="w-full border rounded p-2 bg-gray-100">
                <input type="hidden" name="month" value="<?php echo e($payslip->month); ?>">
                <input type="hidden" name="year" value="<?php echo e($payslip->year); ?>">
            </div>
        </div>

        <!-- Salaire de base et heures sup -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="base_salary" class="block font-medium">Salaire de Base (FCFA)</label>
                <input type="number" name="base_salary" id="base_salary" value="<?php echo e($payslip->base_salary); ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="overtime_hours" class="block font-medium">Heures Supplémentaires</label>
                <input type="number" name="overtime_hours" id="overtime_hours" value="<?php echo e($payslip->overtime_hours); ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="overtime_rate" class="block font-medium">Taux Horaire Supplémentaire (FCFA)</label>
                <input type="number" name="overtime_rate" id="overtime_rate" value="<?php echo e($payslip->overtime_rate); ?>" class="w-full border rounded p-2">
            </div>
        </div>

        <!-- Primes et Allocations -->
        <h3 class="text-xl font-semibold mt-4 mb-2">Primes et Allocations</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="transport_allowance" class="block font-medium">Transport (FCFA)</label>
                <input type="number" name="transport_allowance" id="transport_allowance" value="<?php echo e($payslip->transport_allowance); ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="housing_allowance" class="block font-medium">Logement (FCFA)</label>
                <input type="number" name="housing_allowance" id="housing_allowance" value="<?php echo e($payslip->housing_allowance); ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="meal_allowance" class="block font-medium">Repas (FCFA)</label>
                <input type="number" name="meal_allowance" id="meal_allowance" value="<?php echo e($payslip->meal_allowance); ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="performance_bonus" class="block font-medium">Prime Performance (FCFA)</label>
                <input type="number" name="performance_bonus" id="performance_bonus" value="<?php echo e($payslip->performance_bonus); ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="other_allowances" class="block font-medium">Autres Allocations (FCFA)</label>
                <input type="number" name="other_allowances" id="other_allowances" value="<?php echo e($payslip->other_allowances); ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="allowances_description" class="block font-medium">Description Autres Allocations</label>
                <input type="text" name="allowances_description" id="allowances_description" value="<?php echo e($payslip->allowances_description); ?>" class="w-full border rounded p-2">
            </div>
        </div>

        <!-- Déductions -->
        <h3 class="text-xl font-semibold mt-4 mb-2">Déductions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="social_security" class="block font-medium">Sécurité Sociale (FCFA)</label>
                <input type="number" name="social_security" id="social_security" value="<?php echo e($payslip->social_security); ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="income_tax" class="block font-medium">Impôt sur le Revenu (FCFA)</label>
                <input type="number" name="income_tax" id="income_tax" value="<?php echo e($payslip->income_tax); ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="advance_deduction" class="block font-medium">Avance sur Salaire (FCFA)</label>
                <input type="number" name="advance_deduction" id="advance_deduction" value="<?php echo e($payslip->advance_deduction); ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="loan_deduction" class="block font-medium">Remboursement Prêt (FCFA)</label>
                <input type="number" name="loan_deduction" id="loan_deduction" value="<?php echo e($payslip->loan_deduction); ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="other_deductions" class="block font-medium">Autres Déductions (FCFA)</label>
                <input type="number" name="other_deductions" id="other_deductions" value="<?php echo e($payslip->other_deductions); ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="deductions_description" class="block font-medium">Description Autres Déductions</label>
                <input type="text" name="deductions_description" id="deductions_description" value="<?php echo e($payslip->deductions_description); ?>" class="w-full border rounded p-2">
            </div>
        </div>

        <!-- Notes et Aperçu -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <div>
                <label for="notes" class="block font-medium">Notes / Commentaires</label>
                <textarea name="notes" id="notes" rows="4" class="w-full border rounded p-2"><?php echo e($payslip->notes); ?></textarea>
            </div>
            <div class="bg-gray-50 p-4 rounded shadow">
                <h4 class="font-semibold mb-2">Aperçu des Calculs</h4>
                <p class="mb-1"><strong>Salaire Brut:</strong> <span id="grossSalaryPreview"><?php echo e(number_format($payslip->gross_salary, 0, ',', ' ')); ?></span> FCFA</p>
                <p class="mb-1"><strong>Total Déductions:</strong> <span id="totalDeductionsPreview"><?php echo e(number_format($payslip->total_deductions, 0, ',', ' ')); ?></span> FCFA</p>
                <p class="mb-0"><strong>Salaire Net:</strong> <span id="netSalaryPreview"><?php echo e(number_format($payslip->net_salary, 0, ',', ' ')); ?></span> FCFA</p>
            </div>
        </div>

        <div class="flex justify-end mt-4 space-x-2">
            <a href="<?php echo e(route('hr.payslips.show', $payslip)); ?>" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Enregistrer les Modifications</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[type="number"]');
    inputs.forEach(input => input.addEventListener('input', calculateTotals));

    function calculateTotals() {
        const getVal = id => parseFloat(document.getElementById(id).value) || 0;

        const grossSalary = getVal('base_salary') + getVal('overtime_hours') * getVal('overtime_rate') +
            getVal('transport_allowance') + getVal('housing_allowance') + getVal('meal_allowance') +
            getVal('performance_bonus') + getVal('other_allowances');

        const totalDeductions = getVal('social_security') + getVal('income_tax') + getVal('advance_deduction') +
            getVal('loan_deduction') + getVal('other_deductions');

        const netSalary = grossSalary - totalDeductions;

        const formatNumber = num => new Intl.NumberFormat('fr-FR').format(num);

        document.getElementById('grossSalaryPreview').textContent = formatNumber(grossSalary);
        document.getElementById('totalDeductionsPreview').textContent = formatNumber(totalDeductions);
        document.getElementById('netSalaryPreview').textContent = formatNumber(netSalary);
    }

    calculateTotals();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\payslips\edit.blade.php ENDPATH**/ ?>