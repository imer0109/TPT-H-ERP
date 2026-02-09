

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4">
            <h3 class="text-xl font-semibold flex items-center gap-2">
                <i class="fas fa-balance-scale"></i> Balance Comptable
            </h3>
            <a href="<?php echo e(route('accounting.export.excel', request()->all())); ?>" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>

        <!-- Filtres -->
        <div class="p-6 border-b border-gray-200">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Date de début -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Date de début</label>
                    <input type="date" name="start_date" value="<?php echo e(request('start_date', $start_date)); ?>"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <!-- Date de fin -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Date de fin</label>
                    <input type="date" name="end_date" value="<?php echo e(request('end_date', $end_date)); ?>"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <!-- Société -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Société</label>
                    <select name="company_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Toutes les sociétés</option>
                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($company->id); ?>" <?php echo e(request('company_id') == $company->id ? 'selected' : ''); ?>>
                                <?php echo e($company->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex flex-col justify-end gap-2 mt-2 md:mt-0">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <a href="<?php echo e(route('accounting.balance')); ?>" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-center transition flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Résultats -->
        <div class="p-6">
            <?php if(count($balance_data) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Code Compte</th>
                                <th class="px-4 py-3">Nom du Compte</th>
                                <th class="px-4 py-3 text-right">Débit</th>
                                <th class="px-4 py-3 text-right">Crédit</th>
                                <th class="px-4 py-3 text-right">Solde</th>
                                <th class="px-4 py-3 text-right">Type de Solde</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php 
                                $totalDebit = 0;
                                $totalCredit = 0;
                                $totalBalance = 0;
                            ?>
                            <?php $__currentLoopData = $balance_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $totalDebit += $data['debit'];
                                    $totalCredit += $data['credit'];
                                    $totalBalance += $data['balance'];
                                ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-2"><?php echo e($data['account']->code); ?></td>
                                    <td class="px-4 py-2"><?php echo e($data['account']->name); ?></td>
                                    <td class="px-4 py-2 text-right"><?php echo e(number_format($data['debit'], 2)); ?></td>
                                    <td class="px-4 py-2 text-right"><?php echo e(number_format($data['credit'], 2)); ?></td>
                                    <td class="px-4 py-2 text-right <?php echo e($data['balance'] >= 0 ? 'text-green-600' : 'text-red-500'); ?>">
                                        <?php echo e(number_format(abs($data['balance']), 2)); ?>

                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <?php echo e($data['balance'] >= 0 ? 'Débiteur' : 'Créditeur'); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="bg-gray-50 font-semibold">
                            <tr>
                                <td colspan="2" class="px-4 py-3">TOTAUX</td>
                                <td class="px-4 py-3 text-right"><?php echo e(number_format($totalDebit, 2)); ?></td>
                                <td class="px-4 py-3 text-right"><?php echo e(number_format($totalCredit, 2)); ?></td>
                                <td class="px-4 py-3 text-right"><?php echo e(number_format(abs($totalBalance), 2)); ?></td>
                                <td class="px-4 py-3 text-right">
                                    <?php echo e($totalBalance >= 0 ? 'Débiteur' : 'Créditeur'); ?>

                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-blue-600 text-white rounded-lg p-4 shadow">
                        <h5 class="text-sm opacity-80">Total Débits</h5>
                        <h3 class="text-2xl font-bold"><?php echo e(number_format($totalDebit, 2)); ?></h3>
                    </div>
                    <div class="bg-green-600 text-white rounded-lg p-4 shadow">
                        <h5 class="text-sm opacity-80">Total Crédits</h5>
                        <h3 class="text-2xl font-bold"><?php echo e(number_format($totalCredit, 2)); ?></h3>
                    </div>
                    <div class="<?php echo e($totalBalance >= 0 ? 'bg-indigo-600' : 'bg-yellow-500'); ?> text-white rounded-lg p-4 shadow">
                        <h5 class="text-sm opacity-80">Solde Global</h5>
                        <h3 class="text-2xl font-bold">
                            <?php echo e(number_format(abs($totalBalance), 2)); ?> 
                            (<?php echo e($totalBalance >= 0 ? 'Débiteur' : 'Créditeur'); ?>)
                        </h3>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-10 text-gray-600">
                    <i class="fas fa-info-circle text-4xl mb-3"></i>
                    <h5 class="text-lg font-semibold">Aucune donnée trouvée</h5>
                    <p>Modifiez les filtres pour afficher les résultats correspondants.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.querySelector('select[name="company_id"]')?.addEventListener('change', function() {
    this.closest('form').submit();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\balance.blade.php ENDPATH**/ ?>