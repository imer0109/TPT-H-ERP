

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow rounded-lg">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="text-xl font-semibold">Rapport Analytique</h3>
            <a href="<?php echo e(route('accounting.export.excel', request()->all())); ?>" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
        </div>

        <!-- Filters -->
        <form method="GET" class="px-6 py-4 space-y-4 md:space-y-0 md:flex md:space-x-4">
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Centre de Coût</label>
                <select name="cost_center_id" class="w-full border rounded px-3 py-2">
                    <option value="">Tous les centres de coût</option>
                    <?php $__currentLoopData = App\Models\CostCenter::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costCenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($costCenter->id); ?>" <?php echo e(request('cost_center_id') == $costCenter->id ? 'selected' : ''); ?>>
                            <?php echo e($costCenter->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Projet</label>
                <select name="project_id" class="w-full border rounded px-3 py-2">
                    <option value="">Tous les projets</option>
                    <?php $__currentLoopData = App\Models\Project::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($project->id); ?>" <?php echo e(request('project_id') == $project->id ? 'selected' : ''); ?>>
                            <?php echo e($project->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Date début</label>
                <input type="date" name="date_start" value="<?php echo e(request('date_start')); ?>" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Date fin</label>
                <input type="date" name="date_end" value="<?php echo e(request('date_end')); ?>" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center mr-2">
                    <i class="fas fa-search mr-1"></i> Filtrer
                </button>
                <a href="<?php echo e(route('accounting.reports.analytical')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded flex items-center">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </div>
        </form>

        <!-- Results -->
        <?php if($entries->count() > 0): ?>
        <div class="overflow-x-auto px-6 py-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Date</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Journal</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">N° Pièce</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Compte</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Centre de Coût</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Projet</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-700">Débit</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-700">Crédit</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-4 py-2 text-sm"><?php echo e($entry->entry_date->format('d/m/Y')); ?></td>
                            <td class="px-4 py-2 text-sm"><?php echo e($entry->journal->code ?? ''); ?></td>
                            <td class="px-4 py-2 text-sm"><?php echo e($entry->entry_number); ?></td>
                            <td class="px-4 py-2 text-sm"><?php echo e($entry->debitAccount->code ?? $entry->creditAccount->code ?? ''); ?> - <?php echo e($entry->debitAccount->name ?? $entry->creditAccount->name ?? ''); ?></td>
                            <td class="px-4 py-2 text-sm"><?php echo e($entry->costCenter->name ?? ''); ?></td>
                            <td class="px-4 py-2 text-sm"><?php echo e($entry->project->name ?? ''); ?></td>
                            <td class="px-4 py-2 text-sm"><?php echo e($entry->description); ?></td>
                            <td class="px-4 py-2 text-right text-sm"><?php echo e(number_format($entry->debit_amount, 2)); ?></td>
                            <td class="px-4 py-2 text-right text-sm"><?php echo e(number_format($entry->credit_amount, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="bg-gray-100 font-semibold">
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-right">TOTAUX</td>
                        <td class="px-4 py-2 text-right"><?php echo e(number_format($entries->sum('debit_amount'), 2)); ?></td>
                        <td class="px-4 py-2 text-right"><?php echo e(number_format($entries->sum('credit_amount'), 2)); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4">
            <?php echo e($entries->withQueryString()->links()); ?>

        </div>
        <?php else: ?>
            <div class="px-6 py-4">
                <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4 rounded">
                    <i class="fas fa-info-circle mr-2"></i> Aucune écriture trouvée avec les critères sélectionnés.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Auto-submit form on select change
    $('select[name="cost_center_id"], select[name="project_id"]').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\reports\analytical.blade.php ENDPATH**/ ?>