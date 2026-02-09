

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Détails de Présence</h2>

        <div class="flex gap-3">
            <a href="<?php echo e(route('hr.attendances.index')); ?>"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Retour
            </a>


        </div>
    </div>

    <!-- CARD -->
    <div class="bg-white shadow-md rounded-xl p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- INFORMATIONS -->
            <div>
                <table class="w-full text-left border border-gray-200 rounded-lg overflow-hidden">
                    <tbody class="divide-y divide-gray-200">
                        
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 font-semibold text-gray-800 w-40">Employé</th>
                            <td class="px-4 py-3"><?php echo e($attendance->employee->full_name); ?></td>
                        </tr>

                        <tr>
                            <th class="px-4 py-3 font-semibold text-gray-800">Département</th>
                            <td class="px-4 py-3"><?php echo e($attendance->employee->department->nom); ?></td>
                        </tr>

                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 font-semibold text-gray-800">Date</th>
                            <td class="px-4 py-3"><?php echo e($attendance->date->format('d/m/Y')); ?></td>
                        </tr>

                        <tr>
                            <th class="px-4 py-3 font-semibold text-gray-800">Heure d'Arrivée</th>
                            <td class="px-4 py-3">
                                <?php echo e($attendance->check_in ? $attendance->check_in->format('H:i') : '-'); ?>

                            </td>
                        </tr>

                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 font-semibold text-gray-800">Heure de Départ</th>
                            <td class="px-4 py-3">
                                <?php echo e($attendance->check_out ? $attendance->check_out->format('H:i') : '-'); ?>

                            </td>
                        </tr>

                        <tr>
                            <th class="px-4 py-3 font-semibold text-gray-800">Statut</th>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-white text-sm
                                    <?php if($attendance->status_color === 'success'): ?> bg-green-600
                                    <?php elseif($attendance->status_color === 'warning'): ?> bg-yellow-500
                                    <?php elseif($attendance->status_color === 'danger'): ?> bg-red-600
                                    <?php else: ?> bg-gray-600
                                    <?php endif; ?>">
                                    <?php echo e($attendance->status_label); ?>

                                </span>
                            </td>
                        </tr>

                        <?php if($attendance->late_minutes): ?>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 font-semibold text-gray-800">Retard</th>
                            <td class="px-4 py-3"><?php echo e($attendance->late_minutes); ?> minutes</td>
                        </tr>
                        <?php endif; ?>

                        <?php if($attendance->overtime_hours): ?>
                        <tr>
                            <th class="px-4 py-3 font-semibold text-gray-800">Heures Supplémentaires</th>
                            <td class="px-4 py-3"><?php echo e($attendance->overtime_hours); ?> heures</td>
                        </tr>
                        <?php endif; ?>

                        <!-- Heures totalisées -->
                        <tr class="bg-blue-50">
                            <th class="px-4 py-3 font-bold text-blue-700">Heures totalisées</th>
                            <td class="px-4 py-3 font-semibold text-blue-700">
                                <?php echo e($attendance->total_hours ?? '0h'); ?>

                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- PHOTOS + NOTES + QR CODE -->
            <div class="space-y-6">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h5 class="font-semibold mb-2">Photo d’arrivée</h5>
                        <?php if($attendance->check_in_photo): ?>
                            <img src="<?php echo e(Storage::url($attendance->check_in_photo)); ?>"
                                 class="rounded-lg border shadow">
                        <?php else: ?>
                            <p class="text-gray-500">Pas de photo</p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <h5 class="font-semibold mb-2">Photo de départ</h5>
                        <?php if($attendance->check_out_photo): ?>
                            <img src="<?php echo e(Storage::url($attendance->check_out_photo)); ?>"
                                 class="rounded-lg border shadow">
                        <?php else: ?>
                            <p class="text-gray-500">Pas de photo</p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($attendance->notes): ?>
                <div>
                    <h5 class="font-semibold mb-2">Notes</h5>
                    <p class="p-3 bg-gray-100 rounded-lg"><?php echo e($attendance->notes); ?></p>
                </div>
                <?php endif; ?>



            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\attendances\show.blade.php ENDPATH**/ ?>