

<?php $__env->startSection('title', 'Détails de l'Intégration - Portail Fournisseur')

@section('header', 'Détails de l'intégration'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <a href="<?php echo e(route('supplier.portal.integrations.index')); ?>" 
       class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
    </a>
    
    <div class="mt-4 flex sm:mt-0">
        <a href="<?php echo e(route('supplier.portal.integrations.edit', $integration)); ?>" 
           class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mr-2">
            <i class="fas fa-edit mr-1"></i> Modifier
        </a>
        
        <form action="<?php echo e(route('supplier.portal.integrations.sync', $integration)); ?>" method="POST" class="inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mr-2">
                <i class="fas fa-sync mr-1"></i> Synchroniser
            </button>
        </form>
        
        <form action="<?php echo e(route('supplier.portal.integrations.destroy', $integration)); ?>" method="POST" class="inline">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" 
                    class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette intégration ?')">
                <i class="fas fa-trash mr-1"></i> Supprimer
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Integration Details -->
    <div class="lg:col-span-2">
        <div class="rounded-lg bg-white p-6 shadow">
            <h2 class="mb-4 text-lg font-bold text-gray-800">Informations de l'intégration</h2>
            
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Système externe</label>
                    <p class="mt-1 text-sm text-gray-900"><?php echo e($integration->external_system); ?></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">ID Externe</label>
                    <p class="mt-1 text-sm text-gray-900"><?php echo e($integration->external_id ?? 'N/A'); ?></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Type d'intégration</label>
                    <p class="mt-1 text-sm text-gray-900"><?php echo e($integration->integration_type_formatted); ?></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Statut</label>
                    <p class="mt-1 text-sm text-gray-900">
                        <?php if($integration->is_active): ?>
                            <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                Actif
                            </span>
                        <?php else: ?>
                            <span class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                Inactif
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Statut de synchronisation</label>
                    <p class="mt-1 text-sm text-gray-900">
                        <?php if($integration->sync_status == 'synced'): ?>
                            <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                Synchronisé
                            </span>
                        <?php elseif($integration->sync_status == 'pending'): ?>
                            <span class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">
                                En attente
                            </span>
                        <?php else: ?>
                            <span class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                Échoué
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Dernière synchronisation</label>
                    <p class="mt-1 text-sm text-gray-900">
                        <?php if($integration->last_sync_at): ?>
                            <?php echo e($integration->last_sync_at->format('d/m/Y H:i')); ?>

                        <?php else: ?>
                            Jamais
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sync Status -->
    <div>
        <div class="rounded-lg bg-white p-6 shadow">
            <h2 class="mb-4 text-lg font-bold text-gray-800">Statistiques de synchronisation</h2>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm font-medium text-gray-500">
                        <span>Statut actuel</span>
                        <span>
                            <?php if($integration->sync_status == 'synced'): ?>
                                <span class="text-green-600">Synchronisé</span>
                            <?php elseif($integration->sync_status == 'pending'): ?>
                                <span class="text-yellow-600">En attente</span>
                            <?php else: ?>
                                <span class="text-red-600">Échoué</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm font-medium text-gray-500">
                        <span>Dernière synchronisation</span>
                        <span>
                            <?php if($integration->last_sync_at): ?>
                                <?php echo e($integration->last_sync_at->diffForHumans()); ?>

                            <?php else: ?>
                                Jamais
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if($integration->sync_error_message): ?>
<div class="mt-6 rounded-lg bg-red-50 p-6 shadow-md">
    <h2 class="mb-4 text-lg font-bold text-red-800">Erreur de synchronisation</h2>
    <div class="prose max-w-none text-red-700">
        <p><?php echo e($integration->sync_error_message); ?></p>
    </div>
</div>
<?php endif; ?>

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
<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\portal\integrations\show.blade.php ENDPATH**/ ?>