

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Évaluer le fournisseur: <?php echo e($fournisseur->raison_sociale); ?></h1>
        <a href="<?php echo e(route('fournisseurs.show', $fournisseur)); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la fiche fournisseur
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="<?php echo e(route('fournisseurs.ratings.store', $fournisseur)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Quality Rating -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Qualité des produits/services <span class="text-red-600">*</span>
                    </label>
                    <div class="flex space-x-1">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <input type="radio" name="quality_rating" value="<?php echo e($i); ?>" id="quality_<?php echo e($i); ?>" 
                                class="hidden" <?php echo e(old('quality_rating') == $i ? 'checked' : ''); ?> required>
                            <label for="quality_<?php echo e($i); ?>" class="cursor-pointer text-2xl <?php echo e(old('quality_rating') >= $i ? 'text-yellow-400' : 'text-gray-300'); ?>" 
                                onmouseover="highlightStars('quality', <?php echo e($i); ?>)" 
                                onmouseout="resetStars('quality', <?php echo e(old('quality_rating', 0)); ?>)">
                                ★
                            </label>
                        <?php endfor; ?>
                    </div>
                    <?php $__errorArgs = ['quality_rating'];
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

                <!-- Delivery Rating -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Respect des délais de livraison <span class="text-red-600">*</span>
                    </label>
                    <div class="flex space-x-1">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <input type="radio" name="delivery_rating" value="<?php echo e($i); ?>" id="delivery_<?php echo e($i); ?>" 
                                class="hidden" <?php echo e(old('delivery_rating') == $i ? 'checked' : ''); ?> required>
                            <label for="delivery_<?php echo e($i); ?>" class="cursor-pointer text-2xl <?php echo e(old('delivery_rating') >= $i ? 'text-yellow-400' : 'text-gray-300'); ?>" 
                                onmouseover="highlightStars('delivery', <?php echo e($i); ?>)" 
                                onmouseout="resetStars('delivery', <?php echo e(old('delivery_rating', 0)); ?>)">
                                ★
                            </label>
                        <?php endfor; ?>
                    </div>
                    <?php $__errorArgs = ['delivery_rating'];
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

                <!-- Responsiveness Rating -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Réactivité <span class="text-red-600">*</span>
                    </label>
                    <div class="flex space-x-1">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <input type="radio" name="responsiveness_rating" value="<?php echo e($i); ?>" id="responsiveness_<?php echo e($i); ?>" 
                                class="hidden" <?php echo e(old('responsiveness_rating') == $i ? 'checked' : ''); ?> required>
                            <label for="responsiveness_<?php echo e($i); ?>" class="cursor-pointer text-2xl <?php echo e(old('responsiveness_rating') >= $i ? 'text-yellow-400' : 'text-gray-300'); ?>" 
                                onmouseover="highlightStars('responsiveness', <?php echo e($i); ?>)" 
                                onmouseout="resetStars('responsiveness', <?php echo e(old('responsiveness_rating', 0)); ?>)">
                                ★
                            </label>
                        <?php endfor; ?>
                    </div>
                    <?php $__errorArgs = ['responsiveness_rating'];
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

                <!-- Pricing Rating -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Rapport qualité-prix <span class="text-red-600">*</span>
                    </label>
                    <div class="flex space-x-1">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <input type="radio" name="pricing_rating" value="<?php echo e($i); ?>" id="pricing_<?php echo e($i); ?>" 
                                class="hidden" <?php echo e(old('pricing_rating') == $i ? 'checked' : ''); ?> required>
                            <label for="pricing_<?php echo e($i); ?>" class="cursor-pointer text-2xl <?php echo e(old('pricing_rating') >= $i ? 'text-yellow-400' : 'text-gray-300'); ?>" 
                                onmouseover="highlightStars('pricing', <?php echo e($i); ?>)" 
                                onmouseout="resetStars('pricing', <?php echo e(old('pricing_rating', 0)); ?>)">
                                ★
                            </label>
                        <?php endfor; ?>
                    </div>
                    <?php $__errorArgs = ['pricing_rating'];
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

                <!-- Comments -->
                <div class="md:col-span-2">
                    <label for="comments" class="block text-sm font-medium text-gray-700 mb-1">
                        Commentaires
                    </label>
                    <textarea name="comments" id="comments" rows="4" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"><?php echo e(old('comments')); ?></textarea>
                    <?php $__errorArgs = ['comments'];
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

            <div class="mt-6 flex justify-end space-x-2">
                <a href="<?php echo e(route('fournisseurs.show', $fournisseur)); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Enregistrer l'évaluation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function highlightStars(category, rating) {
    for (let i = 1; i <= 5; i++) {
        const star = document.querySelector(`label[for="${category}_${i}"]`);
        if (i <= rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    }
}

function resetStars(category, rating) {
    for (let i = 1; i <= 5; i++) {
        const star = document.querySelector(`label[for="${category}_${i}"]`);
        if (i <= rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\ratings\create.blade.php ENDPATH**/ ?>