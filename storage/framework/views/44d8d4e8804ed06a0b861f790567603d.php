

<?php $__env->startSection('title', 'Nouvelle Intégration - Portail Fournisseur'); ?>

<?php $__env->startSection('header', 'Créer une nouvelle intégration'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('supplier.portal.integrations.index')); ?>" 
       class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
    </a>
</div>

<div class="rounded-lg bg-white p-6 shadow">
    <form action="<?php echo e(route('supplier.portal.integrations.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="integration_type" class="block text-sm font-medium text-gray-700 mb-1">Type d'Intégration *</label>
                <select name="integration_type" id="integration_type" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Sélectionner un type</option>
                    <option value="erp">ERP</option>
                    <option value="accounting">Comptabilité</option>
                    <option value="inventory">Gestion de stock</option>
                    <option value="custom">Personnalisé</option>
                </select>
                <?php $__errorArgs = ['integration_type'];
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
            
            <div>
                <label for="external_system" class="block text-sm font-medium text-gray-700 mb-1">Système Externe *</label>
                <input type="text" name="external_system" id="external_system" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                       placeholder="Ex: SAP, Oracle, QuickBooks...">
                <?php $__errorArgs = ['external_system'];
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
            
            <div>
                <label for="external_id" class="block text-sm font-medium text-gray-700 mb-1">ID Externe</label>
                <input type="text" name="external_id" id="external_id"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                       placeholder="ID dans le système externe">
                <?php $__errorArgs = ['external_id'];
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
            
            <div class="md:col-span-2">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Intégration active
                    </label>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <a href="<?php echo e(route('supplier.portal.integrations.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-save mr-2"></i> Créer l'Intégration
            </button>
        </div>
    </form>
</div>

<!-- Integration Information -->
<div class="mt-6 rounded-lg bg-blue-50 p-6 shadow-md">
    <h2 class="mb-4 text-lg font-bold text-gray-800">Informations sur les intégrations</h2>
    <div class="prose max-w-none">
        <p>Les intégrations permettent de synchroniser les données de votre fournisseur avec des systèmes externes tels que :</p>
        <ul class="list-disc pl-5">
            <li><strong>ERP</strong> : Intégration avec des systèmes de gestion des ressources de l'entreprise (SAP, Oracle, etc.)</li>
            <li><strong>Comptabilité</strong> : Synchronisation des données comptables et financières (QuickBooks, Sage, etc.)</li>
            <li><strong>Gestion de stock</strong> : Synchronisation des niveaux de stock et des mouvements</li>
            <li><strong>Personnalisé</strong> : Intégrations spécifiques selon vos besoins</li>
        </ul>
        <p class="mt-3"><strong>Note :</strong> La synchronisation peut être effectuée manuellement ou automatiquement selon la configuration du système externe.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\portal\integrations\create.blade.php ENDPATH**/ ?>