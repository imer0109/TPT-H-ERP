<div class="grid grid-cols-1 md:grid-cols-6 gap-4">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Désignation <span class="text-red-500">*</span></label>
        <input type="text" name="items[<?php echo e($index); ?>][designation]" required 
               value="<?php echo e($item['designation'] ?? ''); ?>"
               placeholder="Nom de l'article ou service"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
        <?php $__errorArgs = ["items.{$index}.designation"];
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

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Produit (optionnel)</label>
        <select name="items[<?php echo e($index); ?>][product_id]" 
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
            <option value="">Sélectionner un produit</option>
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($product->id); ?>" 
                        <?php echo e(($item['product_id'] ?? '') == $product->id ? 'selected' : ''); ?>>
                    <?php echo e($product->libelle); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ["items.{$index}.product_id"];
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

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Quantité <span class="text-red-500">*</span></label>
        <input type="number" name="items[<?php echo e($index); ?>][quantite]" required min="1" 
               value="<?php echo e($item['quantite'] ?? 1); ?>"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
        <?php $__errorArgs = ["items.{$index}.quantite"];
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

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Unité <span class="text-red-500">*</span></label>
        <select name="items[<?php echo e($index); ?>][unite]" required 
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
            <option value="unité" <?php echo e(($item['unite'] ?? '') == 'unité' ? 'selected' : ''); ?>>Unité</option>
            <option value="kg" <?php echo e(($item['unite'] ?? '') == 'kg' ? 'selected' : ''); ?>>Kg</option>
            <option value="litre" <?php echo e(($item['unite'] ?? '') == 'litre' ? 'selected' : ''); ?>>Litre</option>
            <option value="mètre" <?php echo e(($item['unite'] ?? '') == 'mètre' ? 'selected' : ''); ?>>Mètre</option>
            <option value="carton" <?php echo e(($item['unite'] ?? '') == 'carton' ? 'selected' : ''); ?>>Carton</option>
            <option value="boîte" <?php echo e(($item['unite'] ?? '') == 'boîte' ? 'selected' : ''); ?>>Boîte</option>
            <option value="paquet" <?php echo e(($item['unite'] ?? '') == 'paquet' ? 'selected' : ''); ?>>Paquet</option>
            <option value="jour" <?php echo e(($item['unite'] ?? '') == 'jour' ? 'selected' : ''); ?>>Jour</option>
            <option value="heure" <?php echo e(($item['unite'] ?? '') == 'heure' ? 'selected' : ''); ?>>Heure</option>
            <option value="mission" <?php echo e(($item['unite'] ?? '') == 'mission' ? 'selected' : ''); ?>>Mission</option>
        </select>
        <?php $__errorArgs = ["items.{$index}.unite"];
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

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire estimé <span class="text-red-500">*</span></label>
        <input type="number" name="items[<?php echo e($index); ?>][prix_unitaire_estime]" required min="0" step="0.01" 
               value="<?php echo e($item['prix_unitaire_estime'] ?? ''); ?>"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
        <?php $__errorArgs = ["items.{$index}.prix_unitaire_estime"];
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

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="items[<?php echo e($index); ?>][description]" rows="2"
                  placeholder="Description détaillée de l'article"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"><?php echo e($item['description'] ?? ''); ?></textarea>
        <?php $__errorArgs = ["items.{$index}.description"];
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

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Fournisseur suggéré</label>
        <select name="items[<?php echo e($index); ?>][fournisseur_suggere_id]" 
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
            <option value="">Aucun fournisseur suggéré</option>
            <?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($fournisseur->id); ?>" 
                        <?php echo e(($item['fournisseur_suggere_id'] ?? '') == $fournisseur->id ? 'selected' : ''); ?>>
                    <?php echo e($fournisseur->nom); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ["items.{$index}.fournisseur_suggere_id"];
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

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
        <textarea name="items[<?php echo e($index); ?>][notes]" rows="2"
                  placeholder="Notes spécifiques à cet article"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"><?php echo e($item['notes'] ?? ''); ?></textarea>
        <?php $__errorArgs = ["items.{$index}.notes"];
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

<div class="flex justify-between items-center mt-4 pt-4 border-t">
    <div class="text-sm text-gray-600">
        Total estimé: <span class="font-medium item-total">0 FCFA</span>
    </div>
    <button type="button" class="remove-item text-red-600 hover:text-red-800 transition">
        <i class="fas fa-trash mr-1"></i>Supprimer cet article
    </button>
</div><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\purchases\requests\_item_form.blade.php ENDPATH**/ ?>