

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-plug text-indigo-600"></i>
            Connecteurs API
        </h3>
        <div class="flex flex-wrap gap-3">
            <a href="<?php echo e(route('api-connectors.api-connectors.create')); ?>"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-plus"></i> Nouveau Connecteur
            </a>
            <button type="button"
                    data-modal-target="importConfigModal"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
                    onclick="document.getElementById('importConfigModal').classList.remove('hidden')">
                <i class="fas fa-upload"></i> Importer Config
            </button>
        </div>
    </div>

    <!-- Filtres -->
    <form method="GET" class="bg-white shadow rounded-xl p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Société -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Société</label>
                <div class="relative">
                    <select name="company_id"
                        class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 pr-8 text-gray-700
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400 appearance-none">
                        <option value="">Toutes les sociétés</option>
                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($company->id); ?>" <?php echo e(request('company_id') == $company->id ? 'selected' : ''); ?>>
                                <?php echo e($company->raison_sociale); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="absolute right-3 top-3 text-gray-400">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </div>
            </div>

            <!-- Type -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Type</label>
                <div class="relative">
                    <select name="type"
                        class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 pr-8 text-gray-700
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400 appearance-none">
                        <option value="">Tous les types</option>
                        <?php $__currentLoopData = \App\Models\ApiConnector::getConnectorTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('type') == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="absolute right-3 top-3 text-gray-400">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </div>
            </div>

            <!-- Statut -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Statut</label>
                <div class="relative">
                    <select name="status"
                        class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 pr-8 text-gray-700
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400 appearance-none">
                        <option value="">Tous les statuts</option>
                        <?php $__currentLoopData = \App\Models\ApiConnector::getStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('status') == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="absolute right-3 top-3 text-gray-400">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex items-end gap-3">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition w-full sm:w-auto">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="<?php echo e(route('api-connectors.api-connectors.index')); ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition w-full sm:w-auto">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white shadow rounded-xl overflow-hidden">
        <?php if($connectors->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nom</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Société</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Dernière Sync</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Prochaine Sync</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Succès</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        <?php $__currentLoopData = $connectors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $connector): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <strong class="text-gray-800"><?php echo e($connector->name); ?></strong>
                                    <?php if($connector->description): ?>
                                        <br><small class="text-gray-500"><?php echo e(Str::limit($connector->description, 50)); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded">
                                        <?php echo e($connector->getTypeLabel()); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3"><?php echo e($connector->company->raison_sociale); ?></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-semibold rounded
                                        <?php echo e($connector->status === 'active' ? 'bg-green-500 text-white' :
                                           ($connector->status === 'error' ? 'bg-red-500 text-white' : 'bg-gray-400 text-white')); ?>">
                                        <?php echo e($connector->getStatusLabel()); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    <?php if($connector->last_sync_at): ?>
                                        <?php echo e($connector->last_sync_at->format('d/m/Y H:i')); ?>

                                        <br><small><?php echo e($connector->last_sync_at->diffForHumans()); ?></small>
                                    <?php else: ?>
                                        <span class="text-gray-400">Jamais</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    <?php if($connector->next_sync_at): ?>
                                        <?php echo e($connector->next_sync_at->format('d/m/Y H:i')); ?>

                                        <br><small><?php echo e($connector->next_sync_at->diffForHumans()); ?></small>
                                    <?php else: ?>
                                        <span class="text-gray-400">Manuel</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php
                                        $totalSyncs = $connector->syncLogs->count();
                                        $successfulSyncs = $connector->syncLogs->where('status', 'success')->count();
                                        $successRate = $totalSyncs > 0 ? round(($successfulSyncs / $totalSyncs) * 100, 1) : 0;
                                    ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded
                                        <?php echo e($successRate >= 90 ? 'bg-green-500 text-white' :
                                           ($successRate >= 70 ? 'bg-yellow-400 text-gray-900' : 'bg-red-500 text-white')); ?>">
                                        <?php echo e($successRate); ?>%
                                    </span>
                                    <br><small class="text-gray-500"><?php echo e($totalSyncs); ?> syncs</small>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="<?php echo e(route('api-connectors.api-connectors.show', $connector)); ?>"
                                           class="p-2 text-blue-500 hover:bg-blue-100 rounded-lg" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="testConnection('<?php echo e($connector->id); ?>')"
                                                class="p-2 text-green-500 hover:bg-green-100 rounded-lg" title="Tester">
                                            <i class="fas fa-plug"></i>
                                        </button>
                                        <button onclick="syncNow('<?php echo e($connector->id); ?>')"
                                                class="p-2 text-indigo-500 hover:bg-indigo-100 rounded-lg" title="Synchroniser">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                        <a href="<?php echo e(route('api-connectors.api-connectors.edit', $connector)); ?>"
                                           class="p-2 text-yellow-500 hover:bg-yellow-100 rounded-lg" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-200">
                <?php echo e($connectors->withQueryString()->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-info-circle fa-2x mb-3 text-indigo-500"></i>
                <h5 class="text-lg font-semibold">Aucun connecteur configuré</h5>
                <p class="mb-4">Créez votre premier connecteur API pour synchroniser vos données.</p>
                <a href="<?php echo e(route('api-connectors.api-connectors.create')); ?>"
                   class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-plus mr-2"></i> Créer un connecteur
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Import Config -->
<div id="importConfigModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
        <button type="button"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
                onclick="document.getElementById('importConfigModal').classList.add('hidden')">
            <i class="fas fa-times text-lg"></i>
        </button>
        <h5 class="text-xl font-semibold mb-4">Importer Configuration</h5>
        <form method="POST" action="<?php echo e(route('api-connectors.import-config')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Société *</label>
                <select name="company_id" required
                        class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400">
                    <option value="">Sélectionner une société</option>
                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($company->id); ?>"><?php echo e($company->raison_sociale); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Fichier de configuration *</label>
                <input type="file" name="config_file" accept=".json" required
                       class="w-full border border-gray-300 rounded-lg py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400">
                <small class="text-gray-500">Fichier JSON de configuration du connecteur</small>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400"
                        onclick="document.getElementById('importConfigModal').classList.add('hidden')">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Importer
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function testConnection(connectorId) {
    const btn = event.target.closest('button');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch(`/api-connectors/${connectorId}/test-connection`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => toastr[data.success ? 'success' : 'error'](data.message))
    .catch(() => toastr.error('Erreur lors du test'))
    .finally(() => { btn.innerHTML = original; btn.disabled = false; });
}

function syncNow(connectorId) {
    const btn = event.target.closest('button');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch(`/api-connectors/${connectorId}/sync-now`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        toastr[data.success ? 'success' : 'error'](data.message);
        if (data.success) setTimeout(() => location.reload(), 2000);
    })
    .catch(() => toastr.error('Erreur de synchronisation'))
    .finally(() => { btn.innerHTML = original; btn.disabled = false; });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\api-connectors\index.blade.php ENDPATH**/ ?>