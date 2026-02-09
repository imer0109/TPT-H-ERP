

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Générer un Certificat de Travail</h1>
        <p class="mt-1 text-sm text-gray-500">Créer un certificat de travail pour un employé</p>
    </div>

    <!-- Success Message -->
    <?php if(session('success')): ?>
        <div class="mb-4 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800"><?php echo e(session('success')); ?></h3>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Work Certificate Form -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Informations de l'Employé</h3>
        </div>
        <form action="<?php echo e(route('hr.documents.work-certificate.generate', $employee)); ?>" method="POST" id="workCertificateForm">
            <?php echo csrf_field(); ?>
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom complet</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($employee->full_name); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Matricule</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($employee->matricule ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Poste</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($employee->currentPosition->title ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date d'embauche</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($employee->date_embauche ? $employee->date_embauche->format('d/m/Y') : 'N/A'); ?></p>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="reason" class="block text-sm font-medium text-gray-700">Motif du certificat <span class="text-red-500">*</span></label>
                        <select id="reason" name="reason" required
                                class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <option value="">Sélectionner le motif</option>
                            <option value="demande_emploi">Demande d'emploi</option>
                            <option value="pret_bancaire">Prêt bancaire</option>
                            <option value="demande_location">Demande de location</option>
                            <option value="visa">Demande de visa</option>
                            <option value="licenciement">Licenciement</option>
                            <option value="autre">Autre</option>
                        </select>
                        <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Date de fin (si applicable)</label>
                        <input type="date" name="end_date" id="end_date" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                               value="<?php echo e(old('end_date')); ?>">
                        <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="additional_info" class="block text-sm font-medium text-gray-700">Informations complémentaires</label>
                        <textarea id="additional_info" name="additional_info" rows="3" 
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                  placeholder="Informations supplémentaires à inclure dans le certificat..."><?php echo e(old('additional_info')); ?></textarea>
                        <?php $__errorArgs = ['additional_info'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="<?php echo e(route('hr.documents.index')); ?>" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Générer le Certificat
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\hr\documents\work-certificate.blade.php ENDPATH**/ ?>