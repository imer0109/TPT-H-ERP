

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Modifier l'Intégration Client</h1>
        <a href="<?php echo e(route('clients.show', $integration->client)); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Retour au Client
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="<?php echo e(route('clients.integrations.update', $integration)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="client_info" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <div class="font-medium"><?php echo e($integration->client->nom_raison_sociale); ?></div>
                        <div class="text-sm text-gray-500"><?php echo e($integration->client->code_client); ?></div>
                    </div>
                </div>
                
                <div>
                    <label for="integration_type" class="block text-sm font-medium text-gray-700 mb-1">Type d'Intégration *</label>
                    <select name="integration_type" id="integration_type" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        <option value="">Sélectionner un type</option>
                        <option value="crm" <?php echo e(old('integration_type', $integration->integration_type) == 'crm' ? 'selected' : ''); ?>>CRM</option>
                        <option value="marketing" <?php echo e(old('integration_type', $integration->integration_type) == 'marketing' ? 'selected' : ''); ?>>Marketing</option>
                        <option value="erp" <?php echo e(old('integration_type', $integration->integration_type) == 'erp' ? 'selected' : ''); ?>>ERP</option>
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
                           value="<?php echo e(old('external_system', $integration->external_system)); ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                           placeholder="Ex: Mailchimp, WhatsApp Business, Salesforce...">
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
                           value="<?php echo e(old('external_id', $integration->external_id)); ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
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
                
                <div>
                    <label for="sync_status" class="block text-sm font-medium text-gray-700 mb-1">Statut de Synchronisation</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <?php if($integration->sync_status == 'synced'): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Synchronisé
                            </span>
                        <?php elseif($integration->sync_status == 'pending'): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                En attente
                            </span>
                        <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Échoué
                            </span>
                        <?php endif; ?>
                        <div class="text-sm text-gray-500 mt-1">
                            Dernière synchronisation : <?php echo e($integration->last_sync_at ? $integration->last_sync_at->format('d/m/Y H:i') : 'Jamais'); ?>

                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="sync_action" class="block text-sm font-medium text-gray-700 mb-1">Actions</label>
                    <div class="flex space-x-2">
                        <a href="<?php echo e(route('clients.integrations.sync', $integration)); ?>" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            <i class="fas fa-sync mr-1"></i> Synchroniser
                        </a>
                    </div>
                </div>
                
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               <?php echo e(old('is_active', $integration->is_active) ? 'checked' : ''); ?>

                               class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Intégration active
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <a href="<?php echo e(route('clients.show', $integration->client)); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Annuler
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
    
    <!-- Error Message (if any) -->
    <?php if($integration->sync_error_message): ?>
    <div class="mt-6 bg-red-50 rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-red-800 mb-4">Erreur de Synchronisation</h2>
        <div class="prose max-w-none text-red-700">
            <p><?php echo e($integration->sync_error_message); ?></p>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Integration Information -->
    <div class="mt-6 bg-blue-50 rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Informations sur les Intégrations</h2>
        <div class="prose max-w-none">
            <p>Les intégrations permettent de synchroniser les données clients avec des systèmes externes tels que :</p>
            <ul class="list-disc pl-5 mt-2">
                <li><strong>CRM</strong> : Synchronisation des données clients avec des systèmes de gestion de la relation client</li>
                <li><strong>Marketing</strong> : Export des segments clients vers des outils de marketing digital (Mailchimp, WhatsApp Business API, etc.)</li>
                <li><strong>ERP</strong> : Intégration avec des systèmes de gestion des ressources de l'entreprise</li>
            </ul>
            <p class="mt-3"><strong>Note :</strong> La synchronisation peut être effectuée manuellement ou automatiquement selon la configuration du système externe.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\integrations\edit.blade.php ENDPATH**/ ?>