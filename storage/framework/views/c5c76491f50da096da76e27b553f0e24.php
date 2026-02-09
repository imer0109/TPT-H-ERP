<?php $__env->startSection('title', 'Créer un Mouvement de Stock'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">Créer un Mouvement de Stock</h3>
            <a href="<?php echo e(route('stock.movements.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <?php if(session('error')): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('stock.movements.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="type" class="block mb-1 font-medium">Type de Mouvement*</label>
                    <select name="type" id="type" class="border rounded w-full p-2 <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Sélectionner un type</option>
                        <option value="entree" <?php echo e(old('type') == 'entree' ? 'selected' : ''); ?>>Entrée</option>
                        <option value="sortie" <?php echo e(old('type') == 'sortie' ? 'selected' : ''); ?>>Sortie</option>
                    </select>
                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"> <?php echo e($message); ?> </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="source" class="block mb-1 font-medium">Source*</label>
                    <select name="source" id="source" class="border rounded w-full p-2 <?php $__errorArgs = ['source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Sélectionner une source</option>
                        <option value="achat" <?php echo e(old('source') == 'achat' ? 'selected' : ''); ?>>Achat</option>
                        <option value="production" <?php echo e(old('source') == 'production' ? 'selected' : ''); ?>>Production</option>
                        <option value="don" <?php echo e(old('source') == 'don' ? 'selected' : ''); ?>>Don</option>
                        <option value="vente" <?php echo e(old('source') == 'vente' ? 'selected' : ''); ?>>Vente</option>
                        <option value="consommation" <?php echo e(old('source') == 'consommation' ? 'selected' : ''); ?>>Consommation</option>
                        <option value="perte" <?php echo e(old('source') == 'perte' ? 'selected' : ''); ?>>Perte</option>
                        <option value="transfert" <?php echo e(old('source') == 'transfert' ? 'selected' : ''); ?>>Transfert</option>
                    </select>
                    <?php $__errorArgs = ['source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"> <?php echo e($message); ?> </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="warehouse_id" class="block mb-1 font-medium">Dépôt*</label>
                    <select name="warehouse_id" id="warehouse_id" class="border rounded w-full p-2 <?php $__errorArgs = ['warehouse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Sélectionner un dépôt</option>
                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($id); ?>" <?php echo e(old('warehouse_id') == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['warehouse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"> <?php echo e($message); ?> </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="product_id" class="block mb-1 font-medium">Produit*</label>
                    <select name="product_id" id="product_id" class="border rounded w-full p-2 <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Sélectionner un produit</option>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($id); ?>" <?php echo e(old('product_id') == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"> <?php echo e($message); ?> </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="quantite" class="block mb-1 font-medium">Quantité*</label>
                    <input type="number" name="quantite" id="quantite" value="<?php echo e(old('quantite')); ?>" min="1" step="1" required
                        class="border rounded w-full p-2 text-right <?php $__errorArgs = ['quantite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['quantite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"> <?php echo e($message); ?> </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="unite" class="block mb-1 font-medium">Unité*</label>
                    <select name="unite" id="unite" class="border rounded w-full p-2 <?php $__errorArgs = ['unite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Sélectionner une unité</option>
                        <option value="unité" <?php echo e(old('unite') == 'unité' ? 'selected' : ''); ?>>Unité</option>
                        <option value="pièce" <?php echo e(old('unite') == 'pièce' ? 'selected' : ''); ?>>Pièce</option>
                        <option value="kg" <?php echo e(old('unite') == 'kg' ? 'selected' : ''); ?>>Kg</option>
                        <option value="litre" <?php echo e(old('unite') == 'litre' ? 'selected' : ''); ?>>Litre</option>
                        <option value="mètre" <?php echo e(old('unite') == 'mètre' ? 'selected' : ''); ?>>Mètre</option>
                        <option value="pack" <?php echo e(old('unite') == 'pack' ? 'selected' : ''); ?>>Pack</option>
                        <option value="carton" <?php echo e(old('unite') == 'carton' ? 'selected' : ''); ?>>Carton</option>
                    </select>
                    <?php $__errorArgs = ['unite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"> <?php echo e($message); ?> </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="prix_unitaire" class="block mb-1 font-medium">Prix Unitaire*</label>
                    <input type="number" name="prix_unitaire" id="prix_unitaire" value="<?php echo e(old('prix_unitaire')); ?>" min="0" step="0.01" required
                        class="border rounded w-full p-2 text-right <?php $__errorArgs = ['prix_unitaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['prix_unitaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"> <?php echo e($message); ?> </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="reference" class="block mb-1 font-medium">Référence</label>
                    <input type="text" name="reference" id="reference" value="<?php echo e(old('reference')); ?>" placeholder="Facultatif"
                        class="border rounded w-full p-2 <?php $__errorArgs = ['reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"> <?php echo e($message); ?> </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="mb-4">
                <label for="motif" class="block mb-1 font-medium">Motif*</label>
                <input type="text" name="motif" id="motif" value="<?php echo e(old('motif')); ?>" placeholder="Motif du mouvement" required
                    class="border rounded w-full p-2 <?php $__errorArgs = ['motif'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['motif'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"> <?php echo e($message); ?> </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Hidden input for montant_total -->
            <input type="hidden" name="montant_total" id="montant_total_hidden" value="<?php echo e(old('montant_total', '0.00')); ?>">

            <div class="mb-4">
                <label for="montant_total_display" class="block mb-1 font-medium">Montant Total</label>
                <input type="text" id="montant_total_display" readonly class="border rounded w-full p-2 text-right bg-gray-100">
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Enregistrer</button>
                <a href="<?php echo e(route('stock.movements.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Annuler</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantiteInput = document.getElementById('quantite');
        const prixInput = document.getElementById('prix_unitaire');
        const totalInput = document.getElementById('montant_total_display');
        const hiddenTotalInput = document.getElementById('montant_total_hidden');

        function toNumber(raw) {
            if (typeof raw !== 'string') raw = String(raw ?? '');
            // Supprime espaces insécables et normaux
            let s = raw.replace(/[\s\u00A0]/g, '');
            const hasComma = s.includes(',');
            const hasDot = s.includes('.');
            if (hasComma && hasDot) {
                // Cas "1.234,56" (FR): les points sont des milliers, la virgule est décimale
                s = s.replace(/\./g, '').replace(/,/g, '.');
            } else if (hasComma) {
                // Cas "1234,56": virgule décimale
                s = s.replace(/,/g, '.');
            }
            // Garde uniquement chiffres, un seul point décimal et signe
            s = s.replace(/[^0-9.\-]/g, '');
            const n = parseFloat(s);
            return Number.isFinite(n) ? n : 0;
        }

        function updateTotal() {
            const quantite = toNumber(quantiteInput.value);
            const prix = toNumber(prixInput.value);
            const total = quantite * prix;
            
            // Update both display and hidden input
            totalInput.value = total.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            hiddenTotalInput.value = total.toFixed(2);
        }

        ['input', 'change', 'blur'].forEach(evt => {
            quantiteInput.addEventListener(evt, updateTotal);
            prixInput.addEventListener(evt, updateTotal);
        });

        updateTotal(); // Initialisation au chargement
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\movements\create.blade.php ENDPATH**/ ?>