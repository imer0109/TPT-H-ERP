

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-10 px-6">
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h3 class="text-2xl font-semibold text-gray-800">Détails de la Demande de Congé</h3>
            <a href="<?php echo e(route('hr.leaves.index')); ?>"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Colonne gauche -->
                <div>
                    <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <th class="bg-gray-100 text-left px-4 py-2 font-semibold text-gray-700 w-1/3">Employé</th>
                                <td class="px-4 py-2 text-gray-800"><?php echo e($leave->employee->full_name ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100 text-left px-4 py-2 font-semibold text-gray-700">Type de Congé</th>
                                <td class="px-4 py-2 text-gray-800"><?php echo e($leave->leaveType->name ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100 text-left px-4 py-2 font-semibold text-gray-700">Date de Début</th>
                                <td class="px-4 py-2 text-gray-800"><?php echo e($leave->start_date ? $leave->start_date->format('d/m/Y') : 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100 text-left px-4 py-2 font-semibold text-gray-700">Date de Fin</th>
                                <td class="px-4 py-2 text-gray-800"><?php echo e($leave->end_date ? $leave->end_date->format('d/m/Y') : 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100 text-left px-4 py-2 font-semibold text-gray-700">Durée</th>
                                <td class="px-4 py-2 text-gray-800"><?php echo e($leave->duration); ?> jours</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Colonne droite -->
                <div>
                    <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <th class="bg-gray-100 text-left px-4 py-2 font-semibold text-gray-700 w-1/3">Statut</th>
                                <td class="px-4 py-2">
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-semibold
                                        <?php echo e($leave->status === 'approved' ? 'bg-green-100 text-green-700' : ($leave->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')); ?>">
                                        <?php echo e($leave->status_label); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100 text-left px-4 py-2 font-semibold text-gray-700">Motif</th>
                                <td class="px-4 py-2 text-gray-800"><?php echo e($leave->reason); ?></td>
                            </tr>

                            <?php if($leave->supporting_document): ?>
                            <tr>
                                <th class="bg-gray-100 text-left px-4 py-2 font-semibold text-gray-700">Document Justificatif</th>
                                <td class="px-4 py-2">
                                    <a href="<?php echo e(Storage::url($leave->supporting_document)); ?>" target="_blank"
                                        class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition">
                                        <i class="fas fa-download"></i> Télécharger
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>

                            <?php if($leave->status === 'approved'): ?>
                            <tr>
                                <th class="bg-gray-100 text-left px-4 py-2 font-semibold text-gray-700">Approuvé par</th>
                                <td class="px-4 py-2 text-gray-800">
                                    <?php echo e($leave->approver->name ?? 'N/A'); ?> le <?php echo e($leave->approved_at ? $leave->approved_at->format('d/m/Y H:i') : 'N/A'); ?>

                                </td>
                            </tr>
                            <?php elseif($leave->status === 'rejected'): ?>
                            <tr>
                                <th class="bg-gray-100 text-left px-4 py-2 font-semibold text-gray-700">Rejeté par</th>
                                <td class="px-4 py-2 text-gray-800">
                                    <?php echo e($leave->validator->name ?? 'N/A'); ?> le <?php echo e($leave->date_validation ? $leave->date_validation->format('d/m/Y H:i') : 'N/A'); ?>

                                </td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100 text-left px-4 py-2 font-semibold text-gray-700">Motif du Rejet</th>
                                <td class="px-4 py-2 text-gray-800"><?php echo e($leave->rejection_reason); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Boutons d’action -->
            <?php if($leave->status === 'pending' && auth()->user()->can('approve-leaves')): ?>
            <div class="flex justify-end gap-4 border-t border-gray-200 pt-6">
                <button type="button" onclick="approveLeave('<?php echo e($leave->id); ?>')"
                    class="px-5 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                    Approuver
                </button>
                <button type="button" onclick="rejectLeave('<?php echo e($leave->id); ?>')"
                    class="px-5 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                    Rejeter
                </button>
            </div>
            <?php endif; ?>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\leaves\show.blade.php ENDPATH**/ ?>