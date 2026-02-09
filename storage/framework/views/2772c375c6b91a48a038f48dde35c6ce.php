

<?php $__env->startSection('title', 'Documents du fournisseur - ' . $fournisseur->raison_sociale); ?>

<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Documents du fournisseur: <?php echo e($fournisseur->raison_sociale); ?>

                    </h2>
                    <a href="<?php echo e(route('fournisseurs.documents.create', $fournisseur)); ?>" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Ajouter un document
                    </a>
                </div>

                <!-- Supplier Info -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Code Fournisseur</p>
                            <p class="font-semibold"><?php echo e($fournisseur->code_fournisseur); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Documents</p>
                            <p class="font-semibold"><?php echo e($fournisseur->documents()->count()); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Documents Expirant Bientôt</p>
                            <p class="font-semibold"><?php echo e($fournisseur->documents()->expirantBientot()->count()); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Documents Table -->
                <?php if($documents->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date d'expiration
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Taille
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($document->nom); ?></div>
                                    <?php if($document->description): ?>
                                        <div class="text-sm text-gray-500"><?php echo e(Str::limit($document->description, 50)); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($document->type); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e(number_format($document->taille / 1024, 2)); ?> KB
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="<?php echo e(route('fournisseurs.documents.show', [$fournisseur, $document])); ?>" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                    <a href="<?php echo e(route('fournisseurs.documents.edit', [$fournisseur, $document])); ?>" 
                                       class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                                    <a href="<?php echo e(route('fournisseurs.documents.download', [$fournisseur, $document])); ?>" 
                                       class="text-green-600 hover:text-green-900 mr-3">Télécharger</a>
                                    <form action="<?php echo e(route('fournisseurs.documents.destroy', [$fournisseur, $document])); ?>" 
                                          method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document?')">
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    <?php echo e($documents->links()); ?>

                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <p class="text-gray-500">Aucun document trouvé pour ce fournisseur.</p>
                    <a href="<?php echo e(route('fournisseurs.documents.create', $fournisseur)); ?>" 
                       class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Ajouter un document
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\documents\index.blade.php ENDPATH**/ ?>