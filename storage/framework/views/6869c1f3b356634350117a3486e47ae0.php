

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-10 px-6">
    <div class="max-w-7xl mx-auto bg-white shadow-lg rounded-2xl p-8 border border-gray-200">
        
        <!-- En-tête -->
        <div class="flex flex-col md:flex-row justify-between items-center border-b pb-4 mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Gestion des Congés</h3>
            <a href="<?php echo e(route('hr.leaves.create')); ?>"
               class="mt-4 md:mt-0 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
               Nouvelle Demande
            </a>
        </div>

        <!-- Filtres -->
        <form action="<?php echo e(route('hr.leaves.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Tous les statuts</option>
                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>En attente</option>
                <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approuvé</option>
                <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejeté</option>
            </select>

            <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Date de début">

            <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Date de fin">

            <div class="flex gap-2">
                <button type="submit" class="w-1/2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Filtrer
                </button>
                <a href="<?php echo e(route('hr.leaves.index')); ?>"
                   class="w-1/2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                   Réinitialiser
                </a>
            </div>
        </form>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 divide-y divide-gray-200 text-sm">
                <thead class="bg-blue-50 text-gray-700 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left">Employé</th>
                        <th class="px-4 py-3 text-left">Type de Congé</th>
                        <th class="px-4 py-3 text-left">Début</th>
                        <th class="px-4 py-3 text-left">Fin</th>
                        <th class="px-4 py-3 text-center">Durée</th>
                        <th class="px-4 py-3 text-center">Statut</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-800"><?php echo e($leave->employee->full_name ?? 'N/A'); ?></td>
                        <td class="px-4 py-3"><?php echo e($leave->leaveType->name ?? 'N/A'); ?></td>
                        <td class="px-4 py-3"><?php echo e($leave->start_date->format('d/m/Y')); ?></td>
                        <td class="px-4 py-3"><?php echo e($leave->end_date->format('d/m/Y')); ?></td>
                        <td class="px-4 py-3 text-center"><?php echo e($leave->duration); ?> j</td>
                        <td class="px-4 py-3 text-center">
                            <?php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                            ?>
                            <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo e($statusColors[$leave->status] ?? 'bg-gray-100 text-gray-700'); ?>">
                                <?php echo e($leave->status_label ?? $leave->status); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="<?php echo e(route('hr.leaves.show', $leave)); ?>"
                                   class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-xs transition">
                                   Voir
                                </a>

                                <?php if($leave->status === 'pending'): ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve-leaves')): ?>
                                    <button type="button" onclick="approveLeave('<?php echo e($leave->id); ?>')"
                                            class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 text-xs transition">
                                            Approuver
                                    </button>
                                    <button type="button" onclick="rejectLeave('<?php echo e($leave->id); ?>')"
                                            class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-xs transition">
                                            Rejeter
                                    </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500">Aucune demande trouvée.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <?php echo e($leaves->links()); ?>

        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function approveLeave(leaveId) {
    if (confirm('Êtes-vous sûr de vouloir approuver cette demande de congé ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/leaves/${leaveId}/approve`;
        form.innerHTML = `<?php echo csrf_field(); ?>`;
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectLeave(leaveId) {
    const reason = prompt('Veuillez indiquer la raison du rejet :');
    if (reason !== null) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/leaves/${leaveId}/reject`;
        form.innerHTML = `<?php echo csrf_field(); ?>
            <input type="hidden" name="rejection_reason" value="${reason}">`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\leaves\index.blade.php ENDPATH**/ ?>