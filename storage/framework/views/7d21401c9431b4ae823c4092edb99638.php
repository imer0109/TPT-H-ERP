

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Liste des Employés</h2>
            <p class="text-gray-600 mt-1">Gestion des ressources humaines de l'entreprise</p>
        </div>
        <a href="<?php echo e(route('hr.employees.create')); ?>"
           class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nouvel Employé
        </a>
    </div>

    <!-- Carte principale -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase">
                    <tr>
                        <th class="px-4 py-3 border-b">Matricule</th>
                        <th class="px-4 py-3 border-b">Nom Complet</th>
                        <th class="px-4 py-3 border-b">Poste</th>
                        <th class="px-4 py-3 border-b">Département</th>
                        <th class="px-4 py-3 border-b">Email</th>
                        <th class="px-4 py-3 border-b">Téléphone</th>
                        <th class="px-4 py-3 border-b">Statut</th>
                        <th class="px-4 py-3 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-4 py-3 text-gray-700"><?php echo e($employee->matricule); ?></td>
                        <td class="px-4 py-3 text-gray-700"><?php echo e($employee->nom); ?> <?php echo e($employee->prenom); ?></td>
                        <td class="px-4 py-3 text-gray-700"><?php echo e($employee->currentPosition->title ?? 'N/A'); ?></td>
                        <td class="px-4 py-3 text-gray-700"><?php echo e($employee->currentPosition->department->name ?? 'N/A'); ?></td>
                        <td class="px-4 py-3 text-gray-700"><?php echo e($employee->email); ?></td>
                        <td class="px-4 py-3 text-gray-700"><?php echo e($employee->telephone); ?></td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                <?php echo e($employee->status === 'actif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                                <?php echo e(ucfirst($employee->status)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('hr.employees.show', $employee)); ?>"
                                   class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition" title="Voir">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="<?php echo e(route('hr.employees.edit', $employee)); ?>"
                                   class="p-2 rounded-lg bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button type="button"
                                        onclick="confirmDelete('<?php echo e($employee->id); ?>')"
                                        class="p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
            <!-- Pagination -->
            <div class="px-4 py-4 border-t border-gray-200">
                <?php echo e($employees->links('pagination::tailwind')); ?>

            </div>
        </div>
    </div>
</div>
<script>
function confirmDelete(employeeId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet employé ?')) {
        document.getElementById('delete-form-' + employeeId).submit();
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\employees\index.blade.php ENDPATH**/ ?>