

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Modifier le Bon de Commande</h1>
                <p class="text-gray-600 mt-1"><?php echo e($order->code); ?></p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 mt-2">
                    <?php echo e($order->statut); ?>

                </span>
            </div>
            <div class="flex space-x-2">
                <a href="<?php echo e(route('purchases.orders.show', $order)); ?>" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="<?php echo e(route('purchases.orders.update', $order)); ?>" id="order-form">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Informations générales -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations Générales</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="fournisseur_id" class="block text-sm font-medium text-gray-700 mb-1">Fournisseur <span class="text-red-500">*</span></label>
                    <select name="fournisseur_id" id="fournisseur_id" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner un fournisseur</option>
                        <?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($fournisseur->id); ?>" 
                                    <?php echo e(old('fournisseur_id', $order->fournisseur_id) == $fournisseur->id ? 'selected' : ''); ?>>
                                <?php echo e($fournisseur->raison_sociale); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['fournisseur_id'];
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
                    <label for="agency_id" class="block text-sm font-medium text-gray-700 mb-1">Agence <span class="text-red-500">*</span></label>
                    <select name="agency_id" id="agency_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Sélectionner une agence</option>
                        <?php $__currentLoopData = $agencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($agency->id); ?>" 
                                    <?php echo e(old('agency_id', $order->agency_id) == $agency->id ? 'selected' : ''); ?>>
                                <?php echo e($agency->nom); ?>

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
                        <option value="Bien" <?php echo e(old('nature_achat', $order->nature_achat) == 'Bien' ? 'selected' : ''); ?>>Bien</option>
                        <option value="Service" <?php echo e(old('nature_achat', $order->nature_achat) == 'Service' ? 'selected' : ''); ?>>Service</option>
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
                    <label for="devise" class="block text-sm font-medium text-gray-700 mb-1">Devise <span class="text-red-500">*</span></label>
                    <select name="devise" id="devise" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="XOF" <?php echo e(old('devise', $order->devise) == 'XOF' ? 'selected' : ''); ?>>XOF (Franc CFA)</option>
                        <option value="EUR" <?php echo e(old('devise', $order->devise) == 'EUR' ? 'selected' : ''); ?>>EUR (Euro)</option>
                        <option value="USD" <?php echo e(old('devise', $order->devise) == 'USD' ? 'selected' : ''); ?>>USD (Dollar)</option>
                    </select>
                    <?php $__errorArgs = ['devise'];
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
                    <label for="delai_contractuel" class="block text-sm font-medium text-gray-700 mb-1">Délai contractuel</label>
                    <input type="date" name="delai_contractuel" id="delai_contractuel" 
                           value="<?php echo e(old('delai_contractuel', $order->delai_contractuel ? $order->delai_contractuel->format('Y-m-d') : '')); ?>"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <?php $__errorArgs = ['delai_contractuel'];
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
                    <label for="conditions_paiement" class="block text-sm font-medium text-gray-700 mb-1">Conditions de paiement</label>
                    <input type="text" name="conditions_paiement" id="conditions_paiement" 
                           value="<?php echo e(old('conditions_paiement', $order->conditions_paiement)); ?>"
                           placeholder="Ex: 30 jours fin de mois"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <?php $__errorArgs = ['conditions_paiement'];
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
                    <label for="adresse_livraison" class="block text-sm font-medium text-gray-700 mb-1">Adresse de livraison</label>
                    <textarea name="adresse_livraison" id="adresse_livraison" rows="2"
                              placeholder="Adresse complète de livraison"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"><?php echo e(old('adresse_livraison', $order->adresse_livraison)); ?></textarea>
                    <?php $__errorArgs = ['adresse_livraison'];
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
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="2"
                              placeholder="Notes supplémentaires"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"><?php echo e(old('notes', $order->notes)); ?></textarea>
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

        <!-- Articles -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Articles</h2>
                <button type="button" id="add-item" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Ajouter un article
                </button>
            </div>

            <div id="items-container">
                <?php if(old('items')): ?>
                    <?php $__currentLoopData = old('items'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('purchases.orders._item_form', ['index' => $index, 'item' => (object)$item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php elseif($order->items->count() > 0): ?>
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('purchases.orders._item_form', ['index' => $index, 'item' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <?php echo $__env->make('purchases.orders._item_form', ['index' => 0, 'item' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </div>

            <!-- Total -->
            <div class="border-t pt-4 mt-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total HT</p>
                        <p class="text-lg font-bold text-gray-900" id="total-ht"><?php echo e(number_format($order->montant_ht, 0, ',', ' ')); ?> <?php echo e($order->devise); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">TVA (18%)</p>
                        <p class="text-lg font-bold text-gray-900" id="total-tva"><?php echo e(number_format($order->montant_tva, 0, ',', ' ')); ?> <?php echo e($order->devise); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total TTC</p>
                        <p class="text-xl font-bold text-red-600" id="total-ttc"><?php echo e(number_format($order->montant_ttc, 0, ',', ' ')); ?> <?php echo e($order->devise); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-end space-x-4">
                <a href="<?php echo e(route('purchases.orders.show', $order)); ?>" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition">
                    Annuler
                </a>
                <button type="submit" name="action" value="save_draft"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                </button>
                <?php if($order->statut === 'Brouillon'): ?>
                    <button type="submit" name="send_order" value="1"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-paper-plane mr-2"></i>Enregistrer et envoyer
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<!-- Template pour nouvel article -->
<template id="item-template">
    <?php echo $__env->make('purchases.orders._item_form', ['index' => '__INDEX__', 'item' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</template>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = <?php echo e($order->items->count() > 0 ? $order->items->count() : 1); ?>;
    
    // Ajouter un nouvel article
    document.getElementById('add-item').addEventListener('click', function() {
        const template = document.getElementById('item-template');
        const clone = template.content.cloneNode(true);
        
        // Remplacer __INDEX__ par l'index réel et s'assurer que c'est un entier
        const currentIndex = parseInt(itemIndex);
        // Remplacer toutes les occurrences de __INDEX__ par l'index
        clone.innerHTML = clone.innerHTML.replace(/__INDEX__/g, currentIndex);
        // S'assurer que les expressions mathématiques fonctionnent correctement
        clone.innerHTML = clone.innerHTML.replace(/\{\{\s*__INDEX__\s*\+\s*1\s*\}\}/g, currentIndex + 1);
        
        document.getElementById('items-container').appendChild(clone);
        itemIndex++;
        
        calculateTotals();
    });
    
    // Supprimer un article
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
            calculateTotals();
        }
    });
    
    // Calculer les totaux
    function calculateTotals() {
        let totalHT = 0;
        
        document.querySelectorAll('.item-row').forEach(function(row) {
            const quantite = parseFloat(row.querySelector('[name*="[quantite]"]').value) || 0;
            const prix = parseFloat(row.querySelector('[name*="[prix_unitaire]"]').value) || 0;
            totalHT += quantite * prix;
        });
        
        const totalTVA = totalHT * 0.18;
        const totalTTC = totalHT + totalTVA;
        const devise = document.getElementById('devise').value || 'XOF';
        
        document.getElementById('total-ht').textContent = new Intl.NumberFormat('fr-FR').format(totalHT) + ' ' + devise;
        document.getElementById('total-tva').textContent = new Intl.NumberFormat('fr-FR').format(totalTVA) + ' ' + devise;
        document.getElementById('total-ttc').textContent = new Intl.NumberFormat('fr-FR').format(totalTTC) + ' ' + devise;
    }
    
    // Calculer totaux à chaque changement
    document.addEventListener('input', function(e) {
        if (e.target.matches('[name*="[quantite]"], [name*="[prix_unitaire]"]')) {
            calculateTotals();
        }
    });
    
    document.getElementById('devise').addEventListener('change', calculateTotals);
    
    // Calculer totaux initial
    calculateTotals();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\purchases\orders\edit.blade.php ENDPATH**/ ?>