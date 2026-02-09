

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Profil de <?php echo e($user->prenom); ?> <?php echo e($user->nom); ?></h1>
            <div class="flex space-x-2">
                <a href="<?php echo e(route('user-profiles.edit', $user->id)); ?>" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
                <a href="<?php echo e(route('user-profiles.index')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations utilisateur -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Informations personnelles</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Nom</p>
                                <p class="text-lg font-medium text-gray-900"><?php echo e($user->nom); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Prénom</p>
                                <p class="text-lg font-medium text-gray-900"><?php echo e($user->prenom); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="text-lg font-medium text-gray-900"><?php echo e($user->email); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Téléphone</p>
                                <p class="text-lg font-medium text-gray-900"><?php echo e($user->telephone ?? 'Non renseigné'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Informations professionnelles</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Entreprise</p>
                                <p class="text-lg font-medium text-gray-900"><?php echo e($user->company->raison_sociale ?? 'Non assigné'); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Statut</p>
                                <p class="text-lg font-medium">
                                    <?php if($user->statut == 'actif'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Actif
                                        </span>
                                    <?php elseif($user->statut == 'inactif'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-user-clock mr-1"></i> Inactif
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-user-slash mr-1"></i> Suspendu
                                        </span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Rôles et Permissions</h2>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 mb-2">Rôles attribués :</p>
                            <div class="flex flex-wrap gap-2">
                                <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <?php echo e($role->nom); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-gray-500">Aucun rôle attribué</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div>
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <?php if($user->photo): ?>
                                <img src="<?php echo e(Storage::url($user->photo)); ?>" alt="Photo de profil" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                            <?php else: ?>
                                <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center border-4 border-gray-200">
                                    <span class="text-4xl text-gray-500"><?php echo e(substr($user->prenom, 0, 1)); ?><?php echo e(substr($user->nom, 0, 1)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900"><?php echo e($user->prenom); ?> <?php echo e($user->nom); ?></h3>
                        <p class="text-gray-600"><?php echo e($user->email); ?></p>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-500">Créé le</p>
                            <p class="text-sm text-gray-900"><?php echo e($user->created_at->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Dernière mise à jour</p>
                            <p class="text-sm text-gray-900"><?php echo e($user->updated_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Documents</h2>
                    </div>
                    <div class="p-4">
                        <?php $__empty_1 = true; $__currentLoopData = $user->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                <div class="flex items-center">
                                    <i class="fas fa-file text-blue-500 mr-2"></i>
                                    <span class="text-sm text-gray-700 truncate max-w-xs"><?php echo e($document->nom_fichier); ?></span>
                                </div>
                                <a href="#" class="text-blue-600 hover:text-blue-900 text-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-gray-500 text-sm py-2">Aucun document</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
                    <div class="p-4">
                        <form action="<?php echo e(route('user-profiles.destroy', $user->id)); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded flex items-center justify-center">
                                <i class="fas fa-trash mr-2"></i> Supprimer l'utilisateur
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\user-profiles\show.blade.php ENDPATH**/ ?>