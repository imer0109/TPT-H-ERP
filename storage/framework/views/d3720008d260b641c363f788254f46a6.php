

<?php $__env->startSection('title', 'Grand Livre'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Grand Livre</h1>
        <p class="text-gray-600 mt-2">Consultez les mouvements détaillés de chaque compte</p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex-1">
                    <form method="GET" action="<?php echo e(route('accounting.general-ledger')); ?>" class="flex space-x-4">
                        <div class="flex-1">
                            <label for="account_id" class="block text-sm font-medium text-gray-700 mb-1">Compte</label>
                            <select name="account_id" id="account_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Tous les comptes</option>
                                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($account->id); ?>" <?php echo e(request('account_id') == $account->id ? 'selected' : ''); ?>>
                                        <?php echo e($account->code); ?> - <?php echo e($account->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                            <input type="date" name="start_date" id="start_date" value="<?php echo e(request('start_date')); ?>" 
                                   class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                            <input type="date" name="end_date" id="end_date" value="<?php echo e(request('end_date')); ?>" 
                                   class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if(isset($account)): ?>
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <?php echo e($account->code); ?> - <?php echo e($account->name); ?>

                <span class="text-sm font-normal ml-2">Solde: <?php echo e(number_format($account->current_balance ?? 0, 2, ',', ' ')); ?> <?php echo e(config('app.currency')); ?></span>
            </h2>
        </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <?php if(isset($entries) && $entries->count() > 0): ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Journal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Libellé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Débit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Crédit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solde</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                        $running_balance = 0;
                    ?>
                    <?php $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            if ($entry->debit_account_id == $account->id) {
                                $running_balance += $entry->debit_amount;
                            } else {
                                $running_balance -= $entry->credit_amount;
                            }
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($entry->entry_date->format('d/m/Y')); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($entry->journal->name ?? ''); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($entry->reference); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs"><?php echo e($entry->description); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right"><?php echo e($entry->debit_current ? number_format($entry->debit_current, 2, ',', ' ') : ''); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right"><?php echo e($entry->credit_current ? number_format($entry->credit_current, 2, ',', ' ') : ''); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right"><?php echo e(number_format($running_balance, 2, ',', ' ')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="4" class="px-6 py-3 text-sm text-gray-900 text-right">Total</td>
                        <td class="px-6 py-3 text-sm text-gray-900 text-right">
                            <?php echo e(number_format($entries->sum('debit_current'), 2, ',', ' ')); ?>

                        </td>
                        <td class="px-6 py-3 text-sm text-gray-900 text-right">
                            <?php echo e(number_format($entries->sum('credit_current'), 2, ',', ' ')); ?>

                        </td>
                        <td class="px-6 py-3 text-sm text-gray-900 text-right">
                            <?php echo e(number_format($running_balance, 2, ',', ' ')); ?>

                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php elseif(isset($accounts) && $accounts->count() > 0): ?>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h3 class="font-semibold text-gray-800"><?php echo e($account->code); ?> - <?php echo e($account->name); ?></h3>
                        <p class="text-sm text-gray-600 mt-1"><?php echo e($account->description); ?></p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-sm">Solde:</span>
                            <span class="font-medium"><?php echo e(number_format($account->current_balance ?? 0, 2, ',', ' ')); ?> <?php echo e(config('app.currency')); ?></span>
                        </div>
                        <a href="<?php echo e(route('accounting.general-ledger', ['account_id' => $account->id, 'start_date' => request('start_date'), 'end_date' => request('end_date')])); ?>" 
                           class="mt-3 inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded hover:bg-blue-200 transition-colors">
                            Voir détails
                        </a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php else: ?>
            <div class="p-6 text-center text-gray-500">
                <p>Aucune donnée disponible</p>
            </div>
            <?php endif; ?>
        </div>

        <?php if(isset($entries) && $entries->count() > 0): ?>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    Affichage de <?php echo e($entries->count()); ?> entrée(s)
                </div>
                <div class="flex space-x-2">
                    <a href="<?php echo e(route('accounting.export.general-ledger-pdf', request()->all())); ?>" 
                       class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Exporter PDF
                    </a>
                    <a href="<?php echo e(route('accounting.export.general-ledger-excel', request()->all())); ?>" 
                       class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Exporter Excel
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\general-ledger.blade.php ENDPATH**/ ?>