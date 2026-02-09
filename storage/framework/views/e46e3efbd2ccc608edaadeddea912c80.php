

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Nouveau paiement fournisseur</h1>
        <a href="<?php echo e(route('fournisseurs.payments.index')); ?>" class="text-blue-600">Retour</a>
    </div>

    <form method="post" action="<?php echo e(route('fournisseurs.payments.store')); ?>" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
        <?php echo csrf_field(); ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fournisseur *</label>
                <select name="fournisseur_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">Sélectionner un fournisseur</option>
                    <?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($id); ?>" <?php echo e(old('fournisseur_id') == $id ? 'selected' : ''); ?>>
                            <?php echo e($name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['fournisseur_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Facture associée</label>
                <select name="supplier_invoice_id" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Aucune facture</option>
                    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $description): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($id); ?>" <?php echo e(old('supplier_invoice_id') == $id ? 'selected' : ''); ?>>
                            <?php echo e($description); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['supplier_invoice_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de paiement *</label>
                <input type="date" name="date_paiement" value="<?php echo e(old('date_paiement', date('Y-m-d'))); ?>" 
                       class="w-full border border-gray-300 rounded px-3 py-2" required>
                <?php $__errorArgs = ['date_paiement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mode de paiement *</label>
                <select name="mode_paiement" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="virement" <?php echo e(old('mode_paiement') == 'virement' ? 'selected' : ''); ?>>Virement</option>
                    <option value="cheque" <?php echo e(old('mode_paiement') == 'cheque' ? 'selected' : ''); ?>>Chèque</option>
                    <option value="especes" <?php echo e(old('mode_paiement') == 'especes' ? 'selected' : ''); ?>>Espèces</option>
                    <option value="carte" <?php echo e(old('mode_paiement') == 'carte' ? 'selected' : ''); ?>>Carte bancaire</option>
                    <option value="autre" <?php echo e(old('mode_paiement') == 'autre' ? 'selected' : ''); ?>>Autre</option>
                </select>
                <?php $__errorArgs = ['mode_paiement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Montant *</label>
                <input type="number" step="0.01" min="0.01" name="montant" value="<?php echo e(old('montant')); ?>" 
                       class="w-full border border-gray-300 rounded px-3 py-2" required>
                <?php $__errorArgs = ['montant'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Devise</label>
                <input type="text" name="devise" value="<?php echo e(old('devise', 'XAF')); ?>" maxlength="3"
                       class="w-full border border-gray-300 rounded px-3 py-2">
                <?php $__errorArgs = ['devise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Référence paiement</label>
                <input type="text" name="reference_paiement" value="<?php echo e(old('reference_paiement')); ?>" 
                       placeholder="N° chèque, référence virement..."
                       class="w-full border border-gray-300 rounded px-3 py-2">
                <?php $__errorArgs = ['reference_paiement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Justificatif</label>
                <input type="file" name="justificatif" accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full border border-gray-300 rounded px-3 py-2">
                <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (max 2MB)</p>
                <?php $__errorArgs = ['justificatif'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded px-3 py-2"><?php echo e(old('notes')); ?></textarea>
            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Enregistrer le paiement
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\payments\create.blade.php ENDPATH**/ ?>