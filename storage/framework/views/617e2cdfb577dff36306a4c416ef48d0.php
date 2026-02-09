<?php $__env->startSection('content'); ?>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Interactions Clients</h1>
        <div>
            <a href="<?php echo e(route('client-interactions.create')); ?>" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i> Nouvelle Interaction
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="<?php echo e(route('client-interactions.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" placeholder="Rechercher..." 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
            </div>
            
            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                <select name="client_id" id="client_id" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les clients</option>
                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($client->id); ?>" <?php echo e(request('client_id') == $client->id ? 'selected' : ''); ?>>
                            <?php echo e($client->nom_raison_sociale); ?> (<?php echo e($client->code_client); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div>
                <label for="type_interaction" class="block text-sm font-medium text-gray-700 mb-1">Type d'interaction</label>
                <select name="type_interaction" id="type_interaction" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous les types</option>
                    <option value="appel" <?php echo e(request('type_interaction') == 'appel' ? 'selected' : ''); ?>>Appel</option>
                    <option value="email" <?php echo e(request('type_interaction') == 'email' ? 'selected' : ''); ?>>Email</option>
                    <option value="reunion" <?php echo e(request('type_interaction') == 'reunion' ? 'selected' : ''); ?>>Réunion</option>
                    <option value="visite" <?php echo e(request('type_interaction') == 'visite' ? 'selected' : ''); ?>>Visite</option>
                    <option value="autre" <?php echo e(request('type_interaction') == 'autre' ? 'selected' : ''); ?>>Autre</option>
                </select>
            </div>
            
            <div>
                <label for="suivi_necessaire" class="block text-sm font-medium text-gray-700 mb-1">Suivi nécessaire</label>
                <select name="suivi_necessaire" id="suivi_necessaire" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <option value="">Tous</option>
                    <option value="1" <?php echo e(request('suivi_necessaire') == '1' ? 'selected' : ''); ?>>Oui</option>
                    <option value="0" <?php echo e(request('suivi_necessaire') == '0' ? 'selected' : ''); ?>>Non</option>
                </select>
            </div>
            
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="<?php echo e(route('client-interactions.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded ml-2">
                    <i class="fas fa-undo mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des interactions -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Client
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Résultat
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Suivi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Utilisateur
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $interactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <?php echo e($interaction->id); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <a href="<?php echo e(route('clients.show', $interaction->client->id)); ?>" class="text-red-600 hover:text-red-800">
                            <?php echo e($interaction->client->nom_raison_sociale); ?>

                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php if($interaction->type_interaction == 'appel'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-phone-alt mr-1"></i> Appel
                            </span>
                        <?php elseif($interaction->type_interaction == 'email'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-envelope mr-1"></i> Email
                            </span>
                        <?php elseif($interaction->type_interaction == 'reunion'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-users mr-1"></i> Réunion
                            </span>
                        <?php elseif($interaction->type_interaction == 'visite'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-building mr-1"></i> Visite
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-comment-dots mr-1"></i> Autre
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e($interaction->date_interaction->format('d/m/Y H:i')); ?>

                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                        <?php echo e($interaction->resultat ?: '-'); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php if($interaction->suivi_necessaire): ?>
                            <?php if($interaction->suivi_complete): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Complété
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-clock mr-1"></i> En attente
                                </span>
                                <?php if($interaction->date_suivi): ?>
                                    <div class="text-xs text-gray-500 mt-1"><?php echo e($interaction->date_suivi->format('d/m/Y')); ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-times-circle mr-1"></i> Non requis
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e($interaction->user->name); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="<?php echo e(route('client-interactions.show', $interaction->id)); ?>" class="text-blue-600 hover:text-blue-900" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('client-interactions.edit', $interaction->id)); ?>" class="text-yellow-600 hover:text-yellow-900" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('client-interactions.destroy', $interaction->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette interaction ?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                        Aucune interaction trouvée.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <?php echo e($interactions->links()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\client-interactions\index.blade.php ENDPATH**/ ?>