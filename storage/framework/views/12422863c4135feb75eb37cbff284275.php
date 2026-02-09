

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Nouvelle Transaction</h1>
            <a href="<?php echo e(route('cash.registers.show', ['cashRegister' => $cashRegister->id])); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                Retour à la caisse
            </a>
        </div>

        <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo e(session('error')); ?></span>
        </div>
        <?php endif; ?>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Caisse</p>
                            <p class="text-lg font-semibold"><?php echo e($cashRegister->nom); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Solde actuel</p>
                            <p class="text-lg font-semibold"><?php echo e(number_format($cashRegister->solde_actuel, 2, ',', ' ')); ?> FCFA</p>
                        </div>
                    </div>
                </div>

                <form action="<?php echo e(route('cash.registers.transactions.store', ['cashRegister' => $cashRegister->id])); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type de transaction</label>
                            <div class="flex space-x-4">
                                <div class="flex items-center">
                                    <input id="encaissement" name="type" type="radio" value="encaissement" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300" <?php echo e(old('type') == 'encaissement' ? 'checked' : ''); ?> required>
                                    <label for="encaissement" class="ml-2 block text-sm text-gray-700">Encaissement</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="decaissement" name="type" type="radio" value="decaissement" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300" <?php echo e(old('type') == 'decaissement' ? 'checked' : ''); ?>>
                                    <label for="decaissement" class="ml-2 block text-sm text-gray-700">Décaissement</label>
                                </div>
                            </div>
                            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="montant" class="block text-sm font-medium text-gray-700 mb-1">Montant</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="montant" id="montant" step="0.01" min="0.01" value="<?php echo e(old('montant')); ?>" required
                                    class="focus:ring-red-500 focus:border-red-500 border py-2 block w-full pr-12 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">FCFA</span>
                                </div>
                            </div>
                            <?php $__errorArgs = ['montant'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="libelle" class="block text-sm font-medium text-gray-700 mb-1">Libellé</label>
                            <input type="text" name="libelle" id="libelle" value="<?php echo e(old('libelle')); ?>" required
                                class="focus:ring-red-500 border py-2 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <?php $__errorArgs = ['libelle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="nature_operation" class="block text-sm font-medium text-gray-700 mb-1">Nature de l'opération</label>
                            <select name="nature_operation" id="nature_operation" required
                                class="focus:ring-red-500 focus:border-red-500 border py-2 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Sélectionner une nature</option>
                                <?php $__currentLoopData = $natures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($nature->nom); ?>" <?php echo e(old('nature_operation') == $nature->nom ? 'selected' : ''); ?>><?php echo e($nature->nom); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['nature_operation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="mode_paiement" class="block text-sm font-medium text-gray-700 mb-1">Mode de paiement</label>
                            <select name="mode_paiement" id="mode_paiement" required
                                class="focus:ring-red-500 focus:border-red-500 border py-2 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="especes" <?php echo e(old('mode_paiement') == 'especes' ? 'selected' : ''); ?>>Espèces</option>
                                <option value="cheque" <?php echo e(old('mode_paiement') == 'cheque' ? 'selected' : ''); ?>>Chèque</option>
                                <option value="mobile_money" <?php echo e(old('mode_paiement') == 'mobile_money' ? 'selected' : ''); ?>>Mobile Money</option>
                                <option value="virement" <?php echo e(old('mode_paiement') == 'virement' ? 'selected' : ''); ?>>Virement</option>
                            </select>
                            <?php $__errorArgs = ['mode_paiement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="projet" class="block text-sm font-medium text-gray-700 mb-1">Projet (optionnel)</label>
                            <input type="text" name="projet" id="projet" value="<?php echo e(old('projet')); ?>"
                                class="focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <?php $__errorArgs = ['projet'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="justificatif" class="block text-sm font-medium text-gray-700 mb-1">Justificatif (optionnel)</label>
                            <input type="file" name="justificatif" id="justificatif"
                                class="focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Formats acceptés: PDF, JPG, JPEG, PNG (max 2Mo)</p>
                            <?php $__errorArgs = ['justificatif'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <a href="<?php echo e(route('cash.registers.show', ['cashRegister' => $cashRegister->id])); ?>" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Annuler
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\cash\transactions\create.blade.php ENDPATH**/ ?>