

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Nouvelle Demande d'Achat</h1>
                <p class="text-gray-600 mt-1">Créer une nouvelle demande d'achat (DA)</p>
            </div>
            <a href="<?php echo e(route('purchases.requests.index')); ?>" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="<?php echo e(route('purchases.requests.store')); ?>" id="purchase-request-form">
        <?php echo csrf_field(); ?>

        <!-- Informations générales -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations Générales</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">Société <span class="text-red-500">*</span></label>
                    <select name="company_id" id="company_id" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner une société</option>
                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($company->id); ?>" <?php echo e(old('company_id') == $company->id ? 'selected' : ''); ?>>
                                <?php echo e($company->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['company_id'];
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
                    <label for="agency_id" class="block text-sm font-medium text-gray-700 mb-1">Agence</label>
                    <select name="agency_id" id="agency_id" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner une agence</option>
                        <?php $__currentLoopData = $agencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($agency->id); ?>" <?php echo e(old('agency_id') == $agency->id ? 'selected' : ''); ?>>
                                <?php echo e($agency->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['agency_id'];
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
                    <label for="nature_achat" class="block text-sm font-medium text-gray-700 mb-1">Nature de l'achat <span class="text-red-500">*</span></label>
                    <select name="nature_achat" id="nature_achat" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner la nature</option>
                        <option value="Bien" <?php echo e(old('nature_achat') == 'Bien' ? 'selected' : ''); ?>>Bien</option>
                        <option value="Service" <?php echo e(old('nature_achat') == 'Service' ? 'selected' : ''); ?>>Service</option>
                    </select>
                    <?php $__errorArgs = ['nature_achat'];
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
                    <label for="date_echeance_souhaitee" class="block text-sm font-medium text-gray-700 mb-1">Date d'échéance souhaitée</label>
                    <input type="date" name="date_echeance_souhaitee" id="date_echeance_souhaitee" 
                           value="<?php echo e(old('date_echeance_souhaitee')); ?>"
                           min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <?php $__errorArgs = ['date_echeance_souhaitee'];
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

                <div class="md:col-span-2">
                    <label for="designation" class="block text-sm font-medium text-gray-700 mb-1">Désignation <span class="text-red-500">*</span></label>
                    <input type="text" name="designation" id="designation" required 
                           value="<?php echo e(old('designation')); ?>"
                           placeholder="Résumé de la demande d'achat"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <?php $__errorArgs = ['designation'];
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

                <div class="md:col-span-2">
                    <label for="justification" class="block text-sm font-medium text-gray-700 mb-1">Justification / Besoin <span class="text-red-500">*</span></label>
                    <textarea name="justification" id="justification" required rows="3"
                              placeholder="Expliquer le besoin et la justification de cette demande"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"><?php echo e(old('justification')); ?></textarea>
                    <?php $__errorArgs = ['justification'];
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
                    <label for="fournisseur_suggere_id" class="block text-sm font-medium text-gray-700 mb-1">Fournisseur suggéré</label>
                    <select name="fournisseur_suggere_id" id="fournisseur_suggere_id" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Aucun fournisseur suggéré</option>
                        <?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($fournisseur->id); ?>" <?php echo e(old('fournisseur_suggere_id') == $fournisseur->id ? 'selected' : ''); ?>>
                                <?php echo e($fournisseur->nom); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['fournisseur_suggere_id'];
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
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="2"
                              placeholder="Notes supplémentaires"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"><?php echo e(old('notes')); ?></textarea>
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
            </div>
        </div>

        <!-- Articles/Services -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Articles / Services</h2>
                <button type="button" id="add-item" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Ajouter un article
                </button>
            </div>

            <div id="items-container">
                <?php if(old('items')): ?>
                    <?php $__currentLoopData = old('items'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="item-row border rounded-lg p-4 mb-4 bg-gray-50" data-index="<?php echo e($index); ?>">
                            <?php echo $__env->make('purchases.requests._item_form', ['index' => $index, 'item' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="item-row border rounded-lg p-4 mb-4 bg-gray-50" data-index="0">
                        <?php echo $__env->make('purchases.requests._item_form', ['index' => 0, 'item' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Total estimé -->
            <div class="border-t pt-4 mt-4">
                <div class="flex justify-end">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total estimé</p>
                        <p class="text-xl font-bold text-gray-900" id="total-estimate">0 FCFA</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-end space-x-4">
                <a href="<?php echo e(route('purchases.requests.index')); ?>" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition">
                    Annuler
                </a>
                <button type="submit" name="action" value="save_draft"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Enregistrer en brouillon
                </button>
                <button type="submit" name="action" value="submit_for_validation"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-paper-plane mr-2"></i>Soumettre pour validation
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Template pour nouvel article -->
<template id="item-template">
    <div class="item-row border rounded-lg p-4 mb-4 bg-gray-50" data-index="">
        <?php echo $__env->make('purchases.requests._item_form', ['index' => '__INDEX__', 'item' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</template>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = <?php echo e(old('items') ? count(old('items')) : 1); ?>;
    
    // Ajouter un nouvel article
    document.getElementById('add-item').addEventListener('click', function() {
        const template = document.getElementById('item-template');
        const container = document.getElementById('items-container');
        
        let newItem = template.content.cloneNode(true);
        newItem.querySelector('.item-row').setAttribute('data-index', itemIndex);
        
        // Remplacer les placeholders d'index
        const html = newItem.querySelector('.item-row').outerHTML.replace(/__INDEX__/g, itemIndex);
        newItem.querySelector('.item-row').outerHTML = html;
        
        container.appendChild(newItem);
        itemIndex++;
        
        updateTotal();
    });
    
    // Supprimer un article
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const itemRow = e.target.closest('.item-row');
            if (document.querySelectorAll('.item-row').length > 1) {
                itemRow.remove();
                updateTotal();
            } else {
                alert('Vous devez avoir au moins un article.');
            }
        }
    });
    
    // Calculer le total automatiquement
    document.addEventListener('input', function(e) {
        if (e.target.name && (e.target.name.includes('[quantite]') || e.target.name.includes('[prix_unitaire_estime]'))) {
            updateItemTotal(e.target);
            updateTotal();
        }
    });
    
    function updateItemTotal(input) {
        const row = input.closest('.item-row');
        const quantite = row.querySelector('[name*="[quantite]"]').value || 0;
        const prix = row.querySelector('[name*="[prix_unitaire_estime]"]').value || 0;
        const total = quantite * prix;
        
        const totalElement = row.querySelector('.item-total');
        if (totalElement) {
            totalElement.textContent = formatCurrency(total);
        }
    }
    
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(function(row) {
            const quantite = row.querySelector('[name*="[quantite]"]').value || 0;
            const prix = row.querySelector('[name*="[prix_unitaire_estime]"]').value || 0;
            total += quantite * prix;
        });
        
        document.getElementById('total-estimate').textContent = formatCurrency(total);
    }
    
    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
    }
    
    // Calcul initial
    updateTotal();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\purchases\requests\create.blade.php ENDPATH**/ ?>