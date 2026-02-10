

<?php $__env->startSection('title', 'Détails de l\'utilisateur'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Détails de l'utilisateur</h2>
            <div>
                <a href="<?php echo e(route('user-management.edit', $user)); ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="<?php echo e(route('user-management.index')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
        
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations personnelles</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nom</label>
                            <p class="text-gray-900"><?php echo e($user->prenom); ?> <?php echo e($user->nom); ?></p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900"><?php echo e($user->email); ?></p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                            <p class="text-gray-900"><?php echo e($user->telephone ?? 'Non renseigné'); ?></p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Statut</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                <?php echo e($user->statut === 'actif' ? 'bg-green-100 text-green-800' : 
                                   ($user->statut === 'inactif' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800')); ?>">
                                <?php echo e(ucfirst($user->statut)); ?>

                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date de création</label>
                            <p class="text-gray-900"><?php echo e($user->created_at->format('d/m/Y H:i')); ?></p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dernière connexion</label>
                            <p class="text-gray-900"><?php echo e($user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais connecté'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rôles et permissions</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Rôles attribués</label>
                        <div class="flex flex-wrap gap-2">
                            <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    <?php echo e($role->nom); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-gray-500 text-sm">Aucun rôle attribué</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Permissions</label>
                        <div class="flex flex-wrap gap-2">
                            <?php $__empty_1 = true; $__currentLoopData = $user->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <?php echo e($permission->nom); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-gray-500 text-sm">Aucune permission spécifique</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/user-management/show.blade.php ENDPATH**/ ?>