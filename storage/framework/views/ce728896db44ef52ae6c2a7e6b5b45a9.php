

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-10 px-6">
    <div class="max-w-7xl mx-auto bg-white shadow-xl rounded-2xl p-8">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-2xl font-bold text-indigo-600">Journal Comptable</h3>
            <a href="<?php echo e(route('accounting.export.excel', request()->all())); ?>" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
               <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
        </div>

        <!-- Filters -->
        <form method="GET" class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Journal</label>
                <select name="journal_id" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">Tous les journaux</option>
                    <?php $__currentLoopData = $journals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $journal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($journal->id); ?>" <?php echo e(request('journal_id') == $journal->id ? 'selected' : ''); ?>>
                            <?php echo e($journal->code); ?> - <?php echo e($journal->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Date début</label>
                <input type="date" name="date_start" value="<?php echo e(request('date_start')); ?>"
                       class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Date fin</label>
                <input type="date" name="date_end" value="<?php echo e(request('date_end')); ?>"
                       class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition flex items-center">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="<?php echo e(route('accounting.reports.journal')); ?>" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2.5 rounded-lg font-medium transition flex items-center">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>

        <!-- Results -->
        <?php if($entries->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-gray-200">
                    <thead class="bg-indigo-100 text-indigo-800 font-semibold">
                        <tr>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Journal</th>
                            <th class="px-4 py-2">N° Pièce</th>
                            <th class="px-4 py-2">Compte Débit</th>
                            <th class="px-4 py-2">Compte Crédit</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2 text-right">Débit</th>
                            <th class="px-4 py-2 text-right">Crédit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-4 py-2"><?php echo e($entry->entry_date->format('d/m/Y')); ?></td>
                                <td class="px-4 py-2"><?php echo e($entry->journal->code); ?></td>
                                <td class="px-4 py-2"><?php echo e($entry->entry_number); ?></td>
                                <td class="px-4 py-2"><?php echo e($entry->debitAccount->code); ?> - <?php echo e($entry->debitAccount->name); ?></td>
                                <td class="px-4 py-2"><?php echo e($entry->creditAccount->code); ?> - <?php echo e($entry->creditAccount->name); ?></td>
                                <td class="px-4 py-2"><?php echo e($entry->description); ?></td>
                                <td class="px-4 py-2 text-right"><?php echo e(number_format($entry->debit_amount, 2)); ?></td>
                                <td class="px-4 py-2 text-right"><?php echo e(number_format($entry->credit_amount, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot class="bg-gray-100 font-semibold">
                        <tr>
                            <td colspan="6" class="px-4 py-2">TOTAUX</td>
                            <td class="px-4 py-2 text-right"><?php echo e(number_format($entries->sum('debit_amount'), 2)); ?></td>
                            <td class="px-4 py-2 text-right"><?php echo e(number_format($entries->sum('credit_amount'), 2)); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4 flex justify-center">
                <?php echo e($entries->withQueryString()->links()); ?>

            </div>
        <?php else: ?>
            <div class="bg-blue-50 text-blue-800 p-4 rounded-lg text-center">
                <i class="fas fa-info-circle mr-2"></i> Aucune écriture trouvée avec les critères sélectionnés.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    $('select[name="journal_id"]').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\reports\journal.blade.php ENDPATH**/ ?>