<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Réclamations Clients</h1>
        <div>
            <a href="<?php echo e(route('client-reclamations.create')); ?>" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i> Nouvelle réclamation
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="<?php echo e(route('client-reclamations.index')); ?>" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="w-full md:w-auto flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                    placeholder="Rechercher par client, description..." 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
            </div>
            
            <div class="w-full md:w-auto flex-1 min-w-[200px]">
                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                <select name="client_id" id="client_id" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les clients</option>
                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($client->id); ?>" <?php echo e(request('client_id') == $client->id ? 'selected' : ''); ?>>
                            <?php echo e($client->nom_raison_sociale); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div class="w-full md:w-auto flex-1 min-w-[200px]">
                <label for="type_reclamation" class="block text-sm font-medium text-gray-700 mb-1">Type de réclamation</label>
                <select name="type_reclamation" id="type_reclamation" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les types</option>
                    <option value="qualite_produit" <?php echo e(request('type_reclamation') == 'qualite_produit' ? 'selected' : ''); ?>>Qualité produit</option>
                    <option value="service_client" <?php echo e(request('type_reclamation') == 'service_client' ? 'selected' : ''); ?>>Service client</option>
                    <option value="livraison" <?php echo e(request('type_reclamation') == 'livraison' ? 'selected' : ''); ?>>Livraison</option>
                    <option value="facturation" <?php echo e(request('type_reclamation') == 'facturation' ? 'selected' : ''); ?>>Facturation</option>
                    <option value="autre" <?php echo e(request('type_reclamation') == 'autre' ? 'selected' : ''); ?>>Autre</option>
                </select>
            </div>
            
            <div class="w-full md:w-auto flex-1 min-w-[200px]">
                <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" id="statut" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les statuts</option>
                    <option value="ouverte" <?php echo e(request('statut') == 'ouverte' ? 'selected' : ''); ?>>Ouverte</option>
                    <option value="en_cours" <?php echo e(request('statut') == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                    <option value="resolue" <?php echo e(request('statut') == 'resolue' ? 'selected' : ''); ?>>Résolue</option>
                    <option value="fermee" <?php echo e(request('statut') == 'fermee' ? 'selected' : ''); ?>>Fermée</option>
                </select>
            </div>
            
            <div class="w-full md:w-auto">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="<?php echo e(route('client-reclamations.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded ml-2">
                    <i class="fas fa-undo mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des réclamations -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date création</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $reclamations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reclamation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #<?php echo e($reclamation->id); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="<?php echo e(route('clients.show', $reclamation->client)); ?>" class="text-blue-600 hover:text-blue-900">
                                <?php echo e($reclamation->client->nom_raison_sociale); ?>

                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php switch($reclamation->type_reclamation):
                                case ('qualite_produit'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Qualité produit</span>
                                    <?php break; ?>
                                <?php case ('service_client'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Service client</span>
                                    <?php break; ?>
                                <?php case ('livraison'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Livraison</span>
                                    <?php break; ?>
                                <?php case ('facturation'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Facturation</span>
                                    <?php break; ?>
                                <?php default: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Autre</span>
                            <?php endswitch; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                            <?php echo e($reclamation->description); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php switch($reclamation->statut):
                                case ('ouverte'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ouverte</span>
                                    <?php break; ?>
                                <?php case ('en_cours'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">En cours</span>
                                    <?php break; ?>
                                <?php case ('resolue'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Résolue</span>
                                    <?php break; ?>
                                <?php case ('fermee'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Fermée</span>
                                    <?php break; ?>
                            <?php endswitch; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($reclamation->agent ? $reclamation->agent->nom . ' ' . $reclamation->agent->prenom : 'Non assigné'); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($reclamation->created_at->format('d/m/Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('client-reclamations.show', $reclamation)); ?>" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('client-reclamations.edit', $reclamation)); ?>" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('client-reclamations.destroy', $reclamation)); ?>" method="POST" class="inline-block">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Aucune réclamation trouvée
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <?php echo e($reclamations->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\client-reclamations\index.blade.php ENDPATH**/ ?>