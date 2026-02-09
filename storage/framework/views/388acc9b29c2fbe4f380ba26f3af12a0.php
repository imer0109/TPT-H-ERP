

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Écritures Comptables</h1>
                <p class="text-gray-600 mt-1">Liste des écritures comptables</p>
            </div>
            <div>
                <a href="<?php echo e(route('accounting.entries.create')); ?>" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Nouvelle Écriture
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                       placeholder="Numéro, description..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Journal</label>
                <select name="journal_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Tous les journaux</option>
                    <?php $__currentLoopData = $journals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $journal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($journal->id); ?>" <?php echo e(request('journal_id') == $journal->id ? 'selected' : ''); ?>>
                            <?php echo e($journal->code); ?> - <?php echo e($journal->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Société</label>
                <select name="company_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Toutes les sociétés</option>
                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($company->id); ?>" <?php echo e(request('company_id') == $company->id ? 'selected' : ''); ?>>
                            <?php echo e($company->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Tous les statuts</option>
                    <?php $__currentLoopData = \App\Models\AccountingEntry::STATUTS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('status') == $key ? 'selected' : ''); ?>>
                            <?php echo e($value); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des écritures -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Journal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Débit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Crédit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($entry->entry_date->format('d/m/Y')); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($entry->entry_number ?? 'N/A'); ?></div>
                                <?php if($entry->reference_number): ?>
                                    <div class="text-xs text-gray-500"><?php echo e($entry->reference_number); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?php echo e(Str::limit($entry->description, 50)); ?></div>
                                <?php if($entry->debitAccount && $entry->creditAccount): ?>
                                    <div class="text-xs text-gray-500">
                                        <?php echo e($entry->debitAccount->code); ?> → <?php echo e($entry->creditAccount->code); ?>

                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($entry->journal->code ?? 'N/A'); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($entry->company->name ?? 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e(number_format($entry->debit_amount, 0, ',', ' ')); ?> <?php echo e($entry->currency); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e(number_format($entry->credit_amount, 0, ',', ' ')); ?> <?php echo e($entry->currency); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php switch($entry->status):
                                        case ('validee'): ?> bg-green-100 text-green-800 <?php break; ?>
                                        <?php case ('brouillon'): ?> bg-yellow-100 text-yellow-800 <?php break; ?>
                                        <?php case ('exportee'): ?> bg-blue-100 text-blue-800 <?php break; ?>
                                        <?php case ('cloturee'): ?> bg-purple-100 text-purple-800 <?php break; ?>
                                        <?php default: ?> bg-gray-100 text-gray-800
                                    <?php endswitch; ?>">
                                    <?php echo e(\App\Models\AccountingEntry::STATUTS[$entry->status] ?? $entry->status); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <?php if($entry->status === 'brouillon'): ?>
                                        <a href="<?php echo e(route('accounting.entries.edit', $entry)); ?>" 
                                           class="text-green-600 hover:text-green-900" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('accounting.entries.show', $entry)); ?>" 
                                       class="text-blue-600 hover:text-blue-900" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if($entry->status === 'brouillon'): ?>
                                        <form method="POST" action="<?php echo e(route('accounting.entries.validate', $entry)); ?>" 
                                              class="inline" onsubmit="return confirm('Valider cette écriture ?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-purple-600 hover:text-purple-900" title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                Aucune écriture trouvée
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if($entries->hasPages()): ?>
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                <?php echo e($entries->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\entries\index.blade.php ENDPATH**/ ?>