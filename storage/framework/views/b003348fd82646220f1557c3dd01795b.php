

<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold">Gestion des Centres de Coût</h3>
        <button @click="openCreateModal = true" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Nouveau Centre de Coût
        </button>
    </div>

    <!-- Table des centres de coût -->
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Code</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nom</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Société</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Statut</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $costCenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 py-2"><?php echo e($cc->code); ?></td>
                        <td class="px-4 py-2"><?php echo e($cc->name); ?></td>
                        <td class="px-4 py-2"><?php echo e($cc->company->name); ?></td>
                        <td class="px-4 py-2"><?php echo e($cc->description ?? '-'); ?></td>
                        <td class="px-4 py-2 text-center">
                            <span class="px-2 py-1 rounded text-white <?php echo e($cc->is_active ? 'bg-green-500' : 'bg-red-500'); ?>">
                                <?php echo e($cc->is_active ? 'Actif' : 'Inactif'); ?>

                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button @click="editCostCenter(<?php echo e($cc->id); ?>)" class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="<?php echo e(route('accounting.settings.cost-centers.destroy', $cc)); ?>" class="inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Aucun centre de coût trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modals -->
    <div x-data="{ openCreateModal: false, openEditModal: false, editData: {} }">
        <!-- Create Modal -->
        <div x-show="openCreateModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded shadow-lg w-full max-w-md p-6" @click.away="openCreateModal = false">
                <h4 class="text-xl font-semibold mb-4">Nouveau Centre de Coût</h4>
                <form method="POST" action="<?php echo e(route('accounting.settings.cost-centers.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2">
                        <label class="block mb-1">Code *</label>
                        <input type="text" name="code" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1">Nom *</label>
                        <input type="text" name="name" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1">Société *</label>
                        <select name="company_id" required class="w-full border rounded px-3 py-2">
                            <option value="">Sélectionner une société</option>
                            <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($company->id); ?>"><?php echo e($company->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1">Description</label>
                        <textarea name="description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked class="form-checkbox">
                            <span class="ml-2">Actif</span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openCreateModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Créer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="openEditModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded shadow-lg w-full max-w-md p-6" @click.away="openEditModal = false">
                <h4 class="text-xl font-semibold mb-4">Modifier Centre de Coût</h4>
                <form :action="`/accounting/settings/cost-centers/${editData.id}`" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="mb-2">
                        <label class="block mb-1">Code *</label>
                        <input type="text" name="code" x-model="editData.code" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1">Nom *</label>
                        <input type="text" name="name" x-model="editData.name" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1">Société *</label>
                        <select name="company_id" x-model="editData.company_id" required class="w-full border rounded px-3 py-2">
                            <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option :value="<?php echo e($company->id); ?>"><?php echo e($company->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1">Description</label>
                        <textarea name="description" x-model="editData.description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" x-model="editData.is_active" class="form-checkbox">
                            <span class="ml-2">Actif</span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function editCostCenter(id) {
        const costCenters = <?php echo json_encode($costCenters, 15, 512) ?>;
        const cc = costCenters.find(c => c.id === id);
        if (cc) {
            window.Alpine.store('editData', { ...cc });
            document.querySelector('[x-data]').__x.$data.openEditModal = true;
            document.querySelector('[x-data]').__x.$data.editData = {...cc};
        }
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\settings\cost-centers.blade.php ENDPATH**/ ?>