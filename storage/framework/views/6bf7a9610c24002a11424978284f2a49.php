

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-10 px-6">
    <div class="max-w-7xl mx-auto bg-white shadow-xl rounded-2xl p-8">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-2xl font-bold text-indigo-600">Compte de Résultat - Année <?php echo e($year); ?></h3>
            <a href="<?php echo e(route('accounting.export.excel', request()->all())); ?>" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
               <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
        </div>

        <!-- Filters -->
        <form method="GET" class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Année</label>
                <input type="number" name="year" min="2000" max="2100"
                       value="<?php echo e(request('year', $year)); ?>"
                       class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition flex items-center">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="<?php echo e(route('accounting.reports.income-statement')); ?>" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2.5 rounded-lg font-medium transition flex items-center">
                    <i class="fas fa-times mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Revenues -->
            <div class="bg-green-50 rounded-xl shadow-md overflow-hidden">
                <div class="bg-green-500 text-white px-4 py-2 font-semibold">Produits (Classe 7)</div>
                <div class="p-4">
                    <?php if(count($revenueAccounts) > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left border border-gray-200">
                                <thead class="bg-green-100 text-green-800">
                                    <tr>
                                        <th class="px-4 py-2 border-b">Code</th>
                                        <th class="px-4 py-2 border-b">Libellé</th>
                                        <th class="px-4 py-2 border-b text-right">Montant</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-green-100">
                                    <?php $totalRevenue = 0; ?>
                                    <?php $__currentLoopData = $revenueAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $amount = $account->entries->sum('credit') - $account->entries->sum('debit');
                                            $totalRevenue += $amount;
                                        ?>
                                        <?php if($amount != 0): ?>
                                            <tr>
                                                <td class="px-4 py-2"><?php echo e($account->code); ?></td>
                                                <td class="px-4 py-2"><?php echo e($account->name); ?></td>
                                                <td class="px-4 py-2 text-right"><?php echo e(number_format($amount, 2)); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="bg-green-100 font-semibold">
                                    <tr>
                                        <td colspan="2" class="px-4 py-2">TOTAL PRODUITS</td>
                                        <td class="px-4 py-2 text-right"><?php echo e(number_format($totalRevenue, 2)); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-green-800 flex items-center gap-2 p-2">
                            <i class="fas fa-info-circle"></i> Aucun produit trouvé pour cette période.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Expenses -->
            <div class="bg-red-50 rounded-xl shadow-md overflow-hidden">
                <div class="bg-red-500 text-white px-4 py-2 font-semibold">Charges (Classe 6)</div>
                <div class="p-4">
                    <?php if(count($expenseAccounts) > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left border border-gray-200">
                                <thead class="bg-red-100 text-red-800">
                                    <tr>
                                        <th class="px-4 py-2 border-b">Code</th>
                                        <th class="px-4 py-2 border-b">Libellé</th>
                                        <th class="px-4 py-2 border-b text-right">Montant</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-red-100">
                                    <?php $totalExpenses = 0; ?>
                                    <?php $__currentLoopData = $expenseAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $amount = $account->entries->sum('debit') - $account->entries->sum('credit');
                                            $totalExpenses += $amount;
                                        ?>
                                        <?php if($amount != 0): ?>
                                            <tr>
                                                <td class="px-4 py-2"><?php echo e($account->code); ?></td>
                                                <td class="px-4 py-2"><?php echo e($account->name); ?></td>
                                                <td class="px-4 py-2 text-right"><?php echo e(number_format($amount, 2)); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="bg-red-100 font-semibold">
                                    <tr>
                                        <td colspan="2" class="px-4 py-2">TOTAL CHARGES</td>
                                        <td class="px-4 py-2 text-right"><?php echo e(number_format($totalExpenses, 2)); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-red-800 flex items-center gap-2 p-2">
                            <i class="fas fa-info-circle"></i> Aucune charge trouvée pour cette période.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-green-500 text-white rounded-xl shadow-md p-5 text-center">
                <h6 class="font-medium">Total Produits</h6>
                <h3 class="text-2xl font-bold mt-1"><?php echo e(number_format($totalRevenue, 2)); ?></h3>
            </div>
            <div class="bg-red-500 text-white rounded-xl shadow-md p-5 text-center">
                <h6 class="font-medium">Total Charges</h6>
                <h3 class="text-2xl font-bold mt-1"><?php echo e(number_format($totalExpenses, 2)); ?></h3>
            </div>
            <div class="<?php echo e($netIncome >= 0 ? 'bg-green-600' : 'bg-red-600'); ?> text-white rounded-xl shadow-md p-5 text-center">
                <h6 class="font-medium">Résultat Net</h6>
                <h3 class="text-2xl font-bold mt-1"><?php echo e(number_format($netIncome, 2)); ?></h3>
                <small><?php echo e($netIncome >= 0 ? 'Bénéfice' : 'Perte'); ?></small>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\reports\income-statement.blade.php ENDPATH**/ ?>