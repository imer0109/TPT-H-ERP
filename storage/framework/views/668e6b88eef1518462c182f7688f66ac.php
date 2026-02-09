<?php $__env->startSection('title', 'Détails du document - ' . $fournisseur->raison_sociale); ?>

<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Détails du document: <?php echo e($document->nom); ?>

                    </h2>
                    <div>
                        <a href="<?php echo e(route('fournisseurs.documents.index', $fournisseur)); ?>" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Retour
                        </a>
                        <a href="<?php echo e(route('fournisseurs.documents.edit', [$fournisseur, $document])); ?>" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Modifier
                        </a>
                        <form action="<?php echo e(route('fournisseurs.documents.destroy', [$fournisseur, $document])); ?>" 
                              method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document?')">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Document Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Informations du document</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Nom</p>
                                <p class="font-semibold"><?php echo e($document->nom); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Type</p>
                                <p class="font-semibold"><?php echo e($document->type); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Description</p>
                                <p class="font-semibold"><?php echo e($document->description ?? 'N/A'); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Date d'expiration</p>
                                <p class="font-semibold">
                                    <?php if($document->date_expiration): ?>
                                        <?php echo e($document->date_expiration->format('d/m/Y')); ?>

                                        <?php if($document->isExpiringSoon()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                                Expiration proche
                                            </span>
                                        <?php endif; ?>
                                        <?php if($document->isExpired()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                                Expiré
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">N/A</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Statut</p>
                                <p class="font-semibold">
                                    <?php if($document->isExpired()): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Expiré
                                        </span>
                                    <?php elseif($document->isExpiringSoon()): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Expiration proche
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Valide
                                        </span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- File Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Informations du fichier</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Nom du fichier</p>
                                <p class="font-semibold"><?php echo e($document->nom); ?>.<?php echo e($document->extension); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Taille</p>
                                <p class="font-semibold"><?php echo e(number_format($document->taille / 1024, 2)); ?> KB</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Extension</p>
                                <p class="font-semibold"><?php echo e(strtoupper($document->extension)); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Date de téléchargement</p>
                                <p class="font-semibold"><?php echo e($document->created_at->format('d/m/Y H:i')); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Téléchargé par</p>
                                <p class="font-semibold"><?php echo e($document->uploadedBy ? $document->uploadedBy->name : 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex space-x-4">
                    <a href="<?php echo e(route('fournisseurs.documents.download', [$fournisseur, $document])); ?>" 
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Télécharger
                    </a>
                    <?php if(strtolower($document->extension) === 'pdf'): ?>
                        <a href="<?php echo e(route('fournisseurs.documents.view', [$fournisseur, $document])); ?>" 
                           target="_blank"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Voir dans le navigateur
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\documents\show.blade.php ENDPATH**/ ?>