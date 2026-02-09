

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Modifier le Compte Bancaire</h1>
        <a href="<?php echo e(route('bank-accounts.index')); ?>" class="text-gray-600 hover:text-gray-900">
            Retour à la liste
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="<?php echo e(route('bank-accounts.update', $bankAccount)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-4">
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Entité</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="company_id" class="block text-sm font-medium text-gray-700">Société</label>
                            <select name="company_id" id="company_id"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="">Sélectionner une société</option>
                                <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($company->id); ?>" <?php echo e(old('company_id', $bankAccount->company_id) == $company->id ? 'selected' : ''); ?>>
                                        <?php echo e($company->raison_sociale); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['company_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div>
                            <label for="agency_id" class="block text-sm font-medium text-gray-700">Agence</label>
                            <select name="agency_id" id="agency_id"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="">Sélectionner une agence</option>
                                <?php $__currentLoopData = $agencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($agency->id); ?>" <?php echo e(old('agency_id', $bankAccount->agency_id) == $agency->id ? 'selected' : ''); ?>>
                                        <?php echo e($agency->nom); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['agency_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <?php $__errorArgs = ['entity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="bank_name" class="block text-sm font-medium text-gray-700">
                        Nom de la Banque <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="bank_name" id="bank_name"
                           value="<?php echo e(old('bank_name', $bankAccount->bank_name)); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    <?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="account_number" class="block text-sm font-medium text-gray-700">
                        Numéro de Compte <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="account_number" id="account_number"
                           value="<?php echo e(old('account_number', $bankAccount->account_number)); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="iban" class="block text-sm font-medium text-gray-700">IBAN</label>
                    <input type="text" name="iban" id="iban"
                           value="<?php echo e(old('iban', $bankAccount->iban)); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md">
                    <?php $__errorArgs = ['iban'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="bic_swift" class="block text-sm font-medium text-gray-700">BIC/SWIFT</label>
                    <input type="text" name="bic_swift" id="bic_swift"
                           value="<?php echo e(old('bic_swift', $bankAccount->bic_swift)); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md">
                    <?php $__errorArgs = ['bic_swift'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700">
                        Devise <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="currency" id="currency"
                           value="<?php echo e(old('currency', $bankAccount->currency)); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="account_type" class="block text-sm font-medium text-gray-700">
                        Type de Compte <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="account_type" id="account_type"
                           value="<?php echo e(old('account_type', $bankAccount->account_type)); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    <?php $__errorArgs = ['account_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="balance" class="block text-sm font-medium text-gray-700">Solde</label>
                    <input type="number" step="0.01" name="balance" id="balance"
                           value="<?php echo e(old('balance', $bankAccount->balance)); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md">
                    <?php $__errorArgs = ['balance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="is_active" id="is_active"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="1" <?php echo e(old('is_active', $bankAccount->is_active) == 1 ? 'selected' : ''); ?>>Actif</option>
                        <option value="0" <?php echo e(old('is_active', $bankAccount->is_active) == 0 ? 'selected' : ''); ?>>Inactif</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-white font-semibold py-2 px-6 rounded-lg shadow">
                    Mettre à jour le compte bancaire
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\bank-accounts\edit.blade.php ENDPATH**/ ?>