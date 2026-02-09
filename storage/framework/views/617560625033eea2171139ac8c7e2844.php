

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Profils Utilisateurs</h1>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('user-profiles.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Nouvel Utilisateur
            </a>
            <a href="#" onclick="document.getElementById('exportForm').submit();" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-file-export mr-2"></i> Exporter
            </a>
        </div>
    </div>

    <form id="exportForm" action="<?php echo e(route('user-profiles.export')); ?>" method="POST" class="hidden"><?php echo csrf_field(); ?></form>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="<?php echo e(route('user-profiles.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Nom, prénom, email..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2">
            <select name="company_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2">
                <option value="">Toutes les entreprises</option>
                <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($company->id); ?>" <?php echo e(request('company_id') == $company->id ? 'selected' : ''); ?>>
                        <?php echo e($company->raison_sociale); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="statut" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2">
                <option value="">Tous les statuts</option>
                <option value="actif" <?php echo e(request('statut') == 'actif' ? 'selected' : ''); ?>>Actif</option>
                <option value="inactif" <?php echo e(request('statut') == 'inactif' ? 'selected' : ''); ?>>Inactif</option>
                <option value="suspendu" <?php echo e(request('statut') == 'suspendu' ? 'selected' : ''); ?>>Suspendu</option>
            </select>
            <select name="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2">
                <option value="">Tous les rôles</option>
                <option value="administrateur" <?php echo e(request('role') == 'administrateur' ? 'selected' : ''); ?>>Administrateur</option>
                <option value="utilisateur" <?php echo e(request('role') == 'utilisateur' ? 'selected' : ''); ?>>Utilisateur</option>
                <option value="gestionnaire" <?php echo e(request('role') == 'gestionnaire' ? 'selected' : ''); ?>>Gestionnaire</option>
            </select>

            <div class="md:col-span-4 flex justify-end space-x-2 mt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="<?php echo e(route('user-profiles.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-undo mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau responsive -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôles</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entreprise</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-4 py-2 text-sm font-medium text-gray-900"><?php echo e($user->id); ?></td>
                    <td class="px-4 py-2 text-sm text-blue-600 hover:text-blue-800">
                        <a href="<?php echo e(route('user-profiles.show', $user->id)); ?>"><?php echo e($user->prenom); ?> <?php echo e($user->nom); ?></a>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-500"><?php echo e($user->email); ?></td>
                    <td class="px-4 py-2 text-sm">
                        <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                <?php echo e($role->nom); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-500"><?php echo e($user->company->raison_sociale ?? '-'); ?></td>
                    <td class="px-4 py-2 text-sm">
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
                    </td>
                    <td class="px-4 py-2 text-sm text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="<?php echo e(route('user-profiles.show', $user->id)); ?>" class="text-blue-600 hover:text-blue-900" title="Voir"><i class="fas fa-eye"></i></a>
                            <a href="<?php echo e(route('user-profiles.edit', $user->id)); ?>" class="text-yellow-600 hover:text-yellow-900" title="Modifier"><i class="fas fa-edit"></i></a>
                            <form action="<?php echo e(route('user-profiles.destroy', $user->id)); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">Aucun utilisateur trouvé.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4"><?php echo e($users->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\user-profiles\index.blade.php ENDPATH**/ ?>