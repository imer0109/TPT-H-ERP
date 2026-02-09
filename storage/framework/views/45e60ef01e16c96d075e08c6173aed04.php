

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Modifier le Compte Comptable</h1>
                <p class="text-gray-600 mt-1">Modifier les informations du compte <?php echo e($chartOfAccount->code); ?> - <?php echo e($chartOfAccount->label); ?></p>
            </div>
            <div>
                <a href="<?php echo e(route('accounting.chart-of-accounts.index')); ?>" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire de modification -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="<?php echo e(route('accounting.chart-of-accounts.update', $chartOfAccount)); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Société (non modifiable) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Société</label>
                    <input type="text" value="<?php echo e($chartOfAccount->company->name); ?>" disabled
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100">
                </div>

                <!-- Compte parent -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Compte parent</label>
                    <select name="parent_id" id="parent_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Aucun (compte racine)</option>
                        <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($account->id); ?>" <?php echo e(old('parent_id', $chartOfAccount->parent_id) == $account->id ? 'selected' : ''); ?>>
                                <?php echo e($account->code); ?> - <?php echo e($account->label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['parent_id'];
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

                <!-- Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" value="<?php echo e(old('code', $chartOfAccount->code)); ?>" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: 411001">
                    <?php $__errorArgs = ['code'];
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

                <!-- Libellé -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Libellé <span class="text-red-500">*</span></label>
                    <input type="text" name="label" id="label" value="<?php echo e(old('label', $chartOfAccount->label)); ?>" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: Clients - Ventes">
                    <?php $__errorArgs = ['label'];
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

                <!-- Type de compte -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de compte <span class="text-red-500">*</span></label>
                    <select name="account_type" id="account_type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un type</option>
                        <?php $__currentLoopData = \App\Models\ChartOfAccount::ACCOUNT_TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(old('account_type', $chartOfAccount->account_type) == $key ? 'selected' : ''); ?>>
                                <?php echo e($value); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['account_type'];
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

                <!-- Nature du compte -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nature du compte <span class="text-red-500">*</span></label>
                    <select name="account_nature" id="account_nature" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner une nature</option>
                        <?php $__currentLoopData = \App\Models\ChartOfAccount::ACCOUNT_NATURES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(old('account_nature', $chartOfAccount->account_nature) == $key ? 'selected' : ''); ?>>
                                <?php echo e($value); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['account_nature'];
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

                <!-- Statut -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                           <?php echo e(old('is_active', $chartOfAccount->is_active) ? 'checked' : ''); ?>

                           class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Compte actif
                    </label>
                </div>

                <!-- Compte auxiliaire -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_auxiliary" id="is_auxiliary" value="1" 
                           <?php echo e(old('is_auxiliary', $chartOfAccount->is_auxiliary) ? 'checked' : ''); ?>

                           class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="is_auxiliary" class="ml-2 block text-sm text-gray-700">
                        Compte auxiliaire
                    </label>
                </div>

                <!-- Type auxiliaire -->
                <div id="aux_type_container" class="<?php echo e(old('is_auxiliary', $chartOfAccount->is_auxiliary) ? '' : 'hidden'); ?>">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type auxiliaire</label>
                    <select name="aux_type" id="aux_type"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un type</option>
                        <?php $__currentLoopData = \App\Models\ChartOfAccount::AUX_TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(old('aux_type', $chartOfAccount->aux_type) == $key ? 'selected' : ''); ?>>
                                <?php echo e($value); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['aux_type'];
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

                <!-- TVA applicable -->
                <div class="flex items-center">
                    <input type="checkbox" name="vat_applicable" id="vat_applicable" value="1" 
                           <?php echo e(old('vat_applicable', $chartOfAccount->vat_applicable) ? 'checked' : ''); ?>

                           class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="vat_applicable" class="ml-2 block text-sm text-gray-700">
                        TVA applicable
                    </label>
                </div>

                <!-- Code SYSCOHADA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code SYSCOHADA</label>
                    <input type="text" name="syscohada_code" id="syscohada_code" value="<?php echo e(old('syscohada_code', $chartOfAccount->syscohada_code)); ?>"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Ex: 411">
                    <?php $__errorArgs = ['syscohada_code'];
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

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Description du compte..."><?php echo e(old('description', $chartOfAccount->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
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

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="<?php echo e(route('accounting.chart-of-accounts.index')); ?>" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isAuxiliaryCheckbox = document.getElementById('is_auxiliary');
        const auxTypeContainer = document.getElementById('aux_type_container');
        
        isAuxiliaryCheckbox.addEventListener('change', function() {
            if (this.checked) {
                auxTypeContainer.classList.remove('hidden');
            } else {
                auxTypeContainer.classList.add('hidden');
                document.getElementById('aux_type').value = '';
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\chart-of-accounts\edit.blade.php ENDPATH**/ ?>