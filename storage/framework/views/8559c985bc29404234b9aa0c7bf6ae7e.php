<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Fournisseurs</h1>
            <p class="text-gray-600 mt-1">Liste et gestion des fournisseurs de l'entreprise</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?php echo e(route('fournisseurs.create')); ?>" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nouveau Fournisseur
            </a>
            <a href="#" onclick="document.getElementById('exportForm').submit();" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Exporter
            </a>
        </div>
    </div>

    <form id="exportForm" action="<?php echo e(route('fournisseurs.export')); ?>" method="POST" class="hidden"><?php echo csrf_field(); ?></form>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="<?php echo e(route('fournisseurs.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Nom, code, contact..." class="w-full rounded-md border border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 px-3 py-2">
            <select name="societe_id" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 px-3 py-2">
                <option value="">Toutes les sociétés</option>
                <?php $__currentLoopData = $societes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $societe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($societe->id); ?>" <?php echo e(request('societe_id') == $societe->id ? 'selected' : ''); ?>>
                        <?php echo e($societe->raison_sociale); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="activite" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 px-3 py-2">
                <option value="">Toutes les activités</option>
                <option value="transport" <?php echo e(request('activite') == 'transport' ? 'selected' : ''); ?>>Transport</option>
                <option value="logistique" <?php echo e(request('activite') == 'logistique' ? 'selected' : ''); ?>>Logistique</option>
                <option value="matieres_premieres" <?php echo e(request('activite') == 'matieres_premieres' ? 'selected' : ''); ?>>Matières premières</option>
                <option value="services" <?php echo e(request('activite') == 'services' ? 'selected' : ''); ?>>Services</option>
                <option value="autre" <?php echo e(request('activite') == 'autre' ? 'selected' : ''); ?>>Autre</option>
            </select>
            <select name="statut" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 px-3 py-2">
                <option value="">Tous les statuts</option>
                <option value="actif" <?php echo e(request('statut') == 'actif' ? 'selected' : ''); ?>>Actif</option>
                <option value="inactif" <?php echo e(request('statut') == 'inactif' ? 'selected' : ''); ?>>Inactif</option>
            </select>

            <div class="md:col-span-4 flex justify-end gap-2 mt-2">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filtrer
                </button>
                <a href="<?php echo e(route('fournisseurs.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau responsive -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raison sociale</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activité</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Société/Agence</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-4 py-3 text-sm font-medium text-gray-900"><?php echo e($fournisseur->code_fournisseur); ?></td>
                    <td class="px-4 py-3 text-sm text-red-600 hover:text-red-800">
                        <a href="<?php echo e(route('fournisseurs.show', $fournisseur->id)); ?>"><?php echo e($fournisseur->raison_sociale); ?></a>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <?php
                            $activiteClasses = [
                                'transport' => 'bg-blue-100 text-blue-800',
                                'logistique' => 'bg-purple-100 text-purple-800',
                                'matieres_premieres' => 'bg-yellow-100 text-yellow-800',
                                'services' => 'bg-green-100 text-green-800',
                                'autre' => 'bg-gray-100 text-gray-800',
                            ];
                            $activiteIcons = [
                                'transport' => '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                                'logistique' => '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>',
                                'matieres_premieres' => '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
                                'services' => '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>',
                                'autre' => '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0h-4a2 2 0 00-2 2v4a2 2 0 002 2h4a2 2 0 002-2V8a2 2 0 00-2-2z"></path></svg>',
                            ];
                        ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($activiteClasses[$fournisseur->activite] ?? 'bg-gray-100 text-gray-800'); ?>">
                            <?php echo $activiteIcons[$fournisseur->activite] ?? $activiteIcons['autre']; ?>

                            <?php echo e(ucfirst(str_replace('_', ' ', $fournisseur->activite))); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        <div><?php echo e($fournisseur->contact_principal ?? '-'); ?></div>
                        <div class="text-xs"><?php echo e($fournisseur->telephone ?? '-'); ?></div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?php echo e($fournisseur->societe->raison_sociale ?? '-'); ?></td>
                    <td class="px-4 py-3 text-sm">
                        <?php if($fournisseur->statut == 'actif'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Actif
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Inactif
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-sm text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="<?php echo e(route('fournisseurs.show', $fournisseur->id)); ?>" class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition" title="Voir">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="<?php echo e(route('fournisseurs.edit', $fournisseur->id)); ?>" class="p-2 rounded-lg bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition" title="Modifier">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="<?php echo e(route('fournisseurs.destroy', $fournisseur->id)); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">Aucun fournisseur trouvé.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4"><?php echo e($fournisseurs->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/fournisseurs/index.blade.php ENDPATH**/ ?>