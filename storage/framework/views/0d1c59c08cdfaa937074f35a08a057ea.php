

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-2xl p-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Nouveau Pointage</h2>

        <form action="<?php echo e(route('hr.attendances.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Employé -->
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Employé*</label>
                    <select name="employee_id" id="employee_id"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Sélectionner un employé</option>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($employee->id); ?>" <?php echo e(old('employee_id') == $employee->id ? 'selected' : ''); ?>>
                            <?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?>

                            <?php if($employee->currentPosition): ?>
                                - <?php echo e($employee->currentPosition->title); ?>

                            <?php endif; ?>
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date*</label>
                    <input type="date" name="date" id="date" value="<?php echo e(old('date', date('Y-m-d'))); ?>"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Heure d'Arrivée -->
                <div>
                    <label for="check_in" class="block text-sm font-medium text-gray-700 mb-1">Heure d'Arrivée</label>
                    <input type="time" name="check_in" id="check_in" value="<?php echo e(old('check_in', date('H:i'))); ?>"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['check_in'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Heure de Départ -->
                <div>
                    <label for="check_out" class="block text-sm font-medium text-gray-700 mb-1">Heure de Départ</label>
                    <input type="time" name="check_out" id="check_out" value="<?php echo e(old('check_out')); ?>"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['check_out'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Statut -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut*</label>
                    <select name="status" id="status"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Sélectionner un statut</option>
                        <option value="present" <?php echo e(old('status') == 'present' ? 'selected' : ''); ?>>Présent</option>
                        <option value="absent" <?php echo e(old('status') == 'absent' ? 'selected' : ''); ?>>Absent</option>
                        <option value="late" <?php echo e(old('status') == 'late' ? 'selected' : ''); ?>>En retard</option>
                        <option value="half_day" <?php echo e(old('status') == 'half_day' ? 'selected' : ''); ?>>Demi-journée</option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Minutes de Retard -->
                <div>
                    <label for="late_minutes" class="block text-sm font-medium text-gray-700 mb-1">Minutes de Retard</label>
                    <input type="number" name="late_minutes" id="late_minutes" value="<?php echo e(old('late_minutes', 0)); ?>" min="0"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['late_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?php echo e(old('notes')); ?></textarea>
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Photo de Pointage -->
                <div class="md:col-span-2">
                    <label for="check_in_photo" class="block text-sm font-medium text-gray-700 mb-1">Photo de Pointage (Optionnelle)</label>
                    <input type="file" name="check_in_photo" id="check_in_photo" accept="image/*"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-gray-500 text-sm mt-1">Formats acceptés : JPG, PNG (max 2MB)</p>
                    <?php $__errorArgs = ['check_in_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

            </div>

            <!-- Boutons -->
            <div class="mt-6 flex justify-end gap-4">
                <a href="<?php echo e(route('hr.attendances.index')); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const checkInField = document.getElementById('check_in');
    const checkOutField = document.getElementById('check_out');
    const lateMinutesField = document.getElementById('late_minutes');

    statusSelect.addEventListener('change', function() {
        if (this.value === 'absent') {
            checkInField.value = '';
            checkOutField.value = '';
            lateMinutesField.value = 0;
            checkInField.disabled = true;
            checkOutField.disabled = true;
        } else {
            checkInField.disabled = false;
            checkOutField.disabled = false;
        }
    });

    checkInField.addEventListener('change', function() {
        if (this.value && statusSelect.value !== 'absent') {
            const checkInTime = new Date('1970-01-01T' + this.value + ':00');
            const standardTime = new Date('1970-01-01T08:00:00');
            if (checkInTime > standardTime) {
                const diffMinutes = Math.floor((checkInTime - standardTime) / (1000 * 60));
                lateMinutesField.value = diffMinutes;
                if (statusSelect.value === 'present') {
                    statusSelect.value = 'late';
                }
            } else {
                lateMinutesField.value = 0;
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\attendances\create.blade.php ENDPATH**/ ?>