

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-semibold mb-6">Créer une Fiche de Paie</h2>

    <form action="<?php echo e(route('hr.payslips.store')); ?>" method="POST" id="payslipForm" class="space-y-6 bg-white p-6 rounded-lg shadow">
        <?php echo csrf_field(); ?>

        <!-- Informations Employé & Période -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="company_id" class="block font-medium">Entreprise <span class="text-red-500">*</span></label>
                <select name="company_id" id="company_id" class="w-full border rounded p-2 <?php $__errorArgs = ['company_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Sélectionner une entreprise</option>
                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($company->id); ?>"><?php echo e($company->raison_sociale); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['company_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="employee_id" class="block font-medium">Employé <span class="text-red-500">*</span></label>
                <select name="employee_id" id="employee_id" class="w-full border rounded p-2 <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Sélectionner un employé</option>
                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($employee->id); ?>" data-salary="<?php echo e($employee->salaire_base); ?>">
                            <?php echo e($employee->prenom); ?> <?php echo e($employee->nom); ?> - <?php echo e($employee->currentPosition->title ?? 'N/A'); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="period_start" class="block font-medium">Date de début <span class="text-red-500">*</span></label>
                <input type="date" name="period_start" id="period_start" class="w-full border rounded p-2 <?php $__errorArgs = ['period_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <?php $__errorArgs = ['period_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="period_end" class="block font-medium">Date de fin <span class="text-red-500">*</span></label>
                <input type="date" name="period_end" id="period_end" class="w-full border rounded p-2 <?php $__errorArgs = ['period_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <?php $__errorArgs = ['period_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="base_salary" class="block font-medium">Salaire de Base (FCFA) <span class="text-red-500">*</span></label>
                <input type="number" name="base_salary" id="base_salary" placeholder="Salaire de base" min="0" step="1" class="w-full border rounded p-2 <?php $__errorArgs = ['base_salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <?php $__errorArgs = ['base_salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div>
                <label for="payment_method" class="block font-medium">Méthode de Paiement <span class="text-red-500">*</span></label>
                <select name="payment_method" id="payment_method" class="w-full border rounded p-2 <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Sélectionner une méthode</option>
                    <option value="cash">Espèces</option>
                    <option value="bank_transfer">Virement Bancaire</option>
                    <option value="mobile_money">Mobile Money</option>
                </select>
                <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <!-- Heures Supplémentaires et Primes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="overtime_hours" class="block font-medium">Heures Supplémentaires</label>
                <input type="number" name="overtime_hours" id="overtime_hours" min="0" step="0.5" value="0" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="overtime_rate" class="block font-medium">Taux Horaire Supplémentaire (FCFA)</label>
                <input type="number" name="overtime_rate" id="overtime_rate" min="0" step="1" value="0" class="w-full border rounded p-2">
            </div>
        </div>

        <h3 class="text-xl font-semibold mt-4 mb-2">Primes et Allocations</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="transport_allowance" class="block font-medium">Transport (FCFA)</label>
                <input type="number" name="transport_allowance" id="transport_allowance" value="0" min="0" step="1" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="housing_allowance" class="block font-medium">Logement (FCFA)</label>
                <input type="number" name="housing_allowance" id="housing_allowance" value="0" min="0" step="1" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="meal_allowance" class="block font-medium">Repas (FCFA)</label>
                <input type="number" name="meal_allowance" id="meal_allowance" value="0" min="0" step="1" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="performance_bonus" class="block font-medium">Prime Performance (FCFA)</label>
                <input type="number" name="performance_bonus" id="performance_bonus" value="0" min="0" step="1" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="other_allowances" class="block font-medium">Autres Allocations (FCFA)</label>
                <input type="number" name="other_allowances" id="other_allowances" value="0" min="0" step="1" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="allowances_description" class="block font-medium">Description Autres Allocations</label>
                <input type="text" name="allowances_description" id="allowances_description" placeholder="Description" class="w-full border rounded p-2">
            </div>
        </div>

        <h3 class="text-xl font-semibold mt-4 mb-2">Déductions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="social_security" class="block font-medium">Sécurité Sociale (FCFA)</label>
                <input type="number" name="social_security" id="social_security" value="0" min="0" step="1" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="income_tax" class="block font-medium">Impôt sur le Revenu (FCFA)</label>
                <input type="number" name="income_tax" id="income_tax" value="0" min="0" step="1" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="advance_deduction" class="block font-medium">Avance sur Salaire (FCFA)</label>
                <input type="number" name="advance_deduction" id="advance_deduction" value="0" min="0" step="1" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="loan_deduction" class="block font-medium">Remboursement Prêt (FCFA)</label>
                <input type="number" name="loan_deduction" id="loan_deduction" value="0" min="0" step="1" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="other_deductions" class="block font-medium">Autres Déductions (FCFA)</label>
                <input type="number" name="other_deductions" id="other_deductions" value="0" min="0" step="1" class="w-full border rounded p-2">
            </div>
            <div>
                <label for="deductions_description" class="block font-medium">Description Autres Déductions</label>
                <input type="text" name="deductions_description" id="deductions_description" placeholder="Description" class="w-full border rounded p-2">
            </div>
        </div>

        <!-- Notes -->
        <div class="mt-4">
            <label for="notes" class="block font-medium">Notes / Commentaires</label>
            <textarea name="notes" id="notes" rows="4" class="w-full border rounded p-2" placeholder="Ajouter des notes ou commentaires"></textarea>
        </div>

        <!-- Aperçu des Calculs -->
        <div class="bg-gray-50 p-4 rounded shadow">
            <h4 class="font-semibold mb-2">Aperçu des Calculs</h4>
            <p class="mb-1"><strong>Salaire Brut:</strong> <span id="grossSalaryPreview">0</span> FCFA</p>
            <p class="mb-1"><strong>Total Déductions:</strong> <span id="totalDeductionsPreview">0</span> FCFA</p>
            <p class="mb-0"><strong>Salaire Net:</strong> <span id="netSalaryPreview">0</span> FCFA</p>
        </div>

        <div class="flex justify-end mt-4 space-x-2">
            <a href="<?php echo e(route('hr.payslips.index')); ?>" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Créer la Fiche</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeSelect = document.getElementById('employee_id');
    const baseSalaryInput = document.getElementById('base_salary');

    // Auto-fill base salary
    employeeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        baseSalaryInput.value = selectedOption.dataset.salary || 0;
        calculateTotals();
    });

    // Calculate totals on input change
    const inputs = document.querySelectorAll('input[type="number"]');
    inputs.forEach(input => input.addEventListener('input', calculateTotals));

    function calculateTotals() {
        const getVal = id => parseFloat(document.getElementById(id).value) || 0;

        const grossSalary = getVal('base_salary') + getVal('overtime_hours') * getVal('overtime_rate') +
            getVal('transport_allowance') + getVal('housing_allowance') + getVal('meal_allowance') +
            getVal('performance_bonus') + getVal('other_allowances');

        const totalDeductions = getVal('social_security') + getVal('income_tax') + 
            getVal('advance_deduction') + getVal('loan_deduction') + getVal('other_deductions');

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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\payslips\create.blade.php ENDPATH**/ ?>