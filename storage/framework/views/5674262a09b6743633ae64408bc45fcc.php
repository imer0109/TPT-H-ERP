<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Clients</h1>
            <p class="text-gray-600 mt-1">Liste et gestion des clients de l'entreprise</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?php echo e(route('clients.create')); ?>" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nouveau Client
            </a>
            <a href="<?php echo e(route('clients.export')); ?>" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Exporter
            </a>
            <a href="<?php echo e(route('clients.dashboard')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Dashboard
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="<?php echo e(route('clients.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                    class="w-full rounded-md border border-gray-300 bg-white text-gray-900 px-3 py-2 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-200" 
                    placeholder="Nom, code, téléphone...">
            </div>

            <div>
                <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">Société</label>
                <select name="company_id" id="company_id" 
                    class="w-full rounded-md border border-gray-300 bg-white text-gray-900 px-3 py-2 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-200">
                    <option value="">Toutes les sociétés</option>
                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($company->id); ?>" <?php echo e(request('company_id') == $company->id ? 'selected' : ''); ?>>
                            <?php echo e($company->raison_sociale); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label for="type_client" class="block text-sm font-medium text-gray-700 mb-1">Type de client</label>
                <select name="type_client" id="type_client" 
                    class="w-full rounded-md border border-gray-300 bg-white text-gray-900 px-3 py-2 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-200">
                    <option value="">Tous les types</option>
                    <option value="particulier" <?php echo e(request('type_client') == 'particulier' ? 'selected' : ''); ?>>Particulier</option>
                    <option value="entreprise" <?php echo e(request('type_client') == 'entreprise' ? 'selected' : ''); ?>>Entreprise</option>
                    <option value="administration" <?php echo e(request('type_client') == 'administration' ? 'selected' : ''); ?>>Administration</option>
                    <option value="distributeur" <?php echo e(request('type_client') == 'distributeur' ? 'selected' : ''); ?>>Distributeur</option>
                </select>
            </div>

            <div>
                <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" id="statut" 
                    class="w-full rounded-md border border-gray-300 bg-white text-gray-900 px-3 py-2 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-200">
                    <option value="">Tous les statuts</option>
                    <option value="actif" <?php echo e(request('statut') == 'actif' ? 'selected' : ''); ?>>Actif</option>
                    <option value="inactif" <?php echo e(request('statut') == 'inactif' ? 'selected' : ''); ?>>Inactif</option>
                    <option value="suspendu" <?php echo e(request('statut') == 'suspendu' ? 'selected' : ''); ?>>Suspendu</option>
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end gap-2">
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filtrer
                </button>
                <a href="<?php echo e(route('clients.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des clients -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom/Raison sociale</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Société</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Encours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo e($client->code_client); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($client->nom_raison_sociale); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php echo e($client->type_client == 'particulier' ? 'bg-blue-100 text-blue-800' : ''); ?>

                            <?php echo e($client->type_client == 'entreprise' ? 'bg-green-100 text-green-800' : ''); ?>

                            <?php echo e($client->type_client == 'administration' ? 'bg-purple-100 text-purple-800' : ''); ?>

                            <?php echo e($client->type_client == 'distributeur' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                        ">
                            <?php echo e(ucfirst($client->type_client)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <div><?php echo e($client->telephone); ?></div>
                        <div><?php echo e($client->email); ?></div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($client->company->raison_sociale ?? 'N/A'); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <div class="font-semibold"><?php echo e(number_format($client->getEncours(), 0, ',', ' ')); ?> FCFA</div>
                        <div class="text-xs text-gray-400"><?php echo e($client->getNombreFacturesImpayees()); ?> facture(s) impayée(s)</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php echo e($client->statut == 'actif' ? 'bg-green-100 text-green-800' : ''); ?>

                            <?php echo e($client->statut == 'inactif' ? 'bg-gray-100 text-gray-800' : ''); ?>

                            <?php echo e($client->statut == 'suspendu' ? 'bg-red-100 text-red-800' : ''); ?>

                        ">
                            <?php echo e(ucfirst($client->statut)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('clients.show', $client)); ?>" class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition" title="Voir">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="<?php echo e(route('clients.edit', $client)); ?>" class="p-2 rounded-lg bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition" title="Modifier">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="<?php echo e(route('clients.destroy', $client)); ?>" method="POST" class="inline-block">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')" title="Supprimer">
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
                    <td colspan="8" class="px-6 py-4 text-sm text-gray-500 text-center">Aucun client trouvé</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="px-6 py-4">
            <?php echo e($clients->links()); ?>

        </div>
    </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\index.blade.php ENDPATH**/ ?>