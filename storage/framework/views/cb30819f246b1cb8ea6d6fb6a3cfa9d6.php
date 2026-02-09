

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Clients</h1>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('clients.index')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-list mr-2"></i> Liste des Clients
            </a>
            <a href="<?php echo e(route('clients.create')); ?>" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i> Nouveau Client
            </a>
            <a href="<?php echo e(route('clients.segments')); ?>" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-users-cog mr-2"></i> Segmentation
            </a>
            <a href="<?php echo e(route('clients.loyalty.dashboard')); ?>" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-crown mr-2"></i> Fidélité
            </a>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Clients</p>
                    <p class="text-2xl font-bold"><?php echo e($totalClients); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-user-check fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Clients Actifs</p>
                    <p class="text-2xl font-bold"><?php echo e($clientsActifs); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Clients à Risque</p>
                    <p class="text-2xl font-bold"><?php echo e($clientsAtRisk); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Chiffre d'Affaires</p>
                    <p class="text-2xl font-bold"><?php echo e(number_format($totalRevenue, 0, ',', ' ')); ?> FCFA</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et tableaux -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top 10 clients par CA -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Top 10 Clients par CA</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CA (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $topClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($client->nom_raison_sociale); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e(number_format($client->ca ?? 0, 0, ',', ' ')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-sm text-gray-500 text-center">Aucun client trouvé</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Répartition par type de client -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Répartition par Type de Client</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                            $totalTypes = $repartitionTypes->sum('total');
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $repartitionTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo e(ucfirst($type->type_client)); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($type->total); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($totalTypes > 0 ? number_format(($type->total / $totalTypes) * 100, 1) : 0); ?>%</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-sm text-gray-500 text-center">Aucune donnée</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Répartition par catégorie -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Répartition par Catégorie</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                            $totalCategories = $repartitionCategories->sum('total');
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $repartitionCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo e($category->categorie == 'or' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                    <?php echo e($category->categorie == 'argent' ? 'bg-gray-100 text-gray-800' : ''); ?>

                                    <?php echo e($category->categorie == 'bronze' ? 'bg-amber-100 text-amber-800' : ''); ?>

                                ">
                                    <?php echo e(ucfirst($category->categorie)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($category->total); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($totalCategories > 0 ? number_format(($category->total / $totalCategories) * 100, 1) : 0); ?>%</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-sm text-gray-500 text-center">Aucune donnée</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Évolution du nombre de clients -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Évolution du Nombre de Clients</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mois/Année</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nouveaux Clients</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $evolutionClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evolution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($evolution->year); ?>-<?php echo e(str_pad($evolution->month, 2, '0', STR_PAD_LEFT)); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($evolution->total); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-sm text-gray-500 text-center">Aucune donnée</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Répartition géographique -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Répartition Géographique des Clients</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Carte géographique (simulation) -->
            <div class="bg-gray-50 rounded-lg p-4 h-80 flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-map-marked-alt text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">Cartographie géographique des clients</p>
                    <p class="text-sm text-gray-500 mt-2">Intégration avec Google Maps ou OpenStreetMap</p>
                </div>
            </div>
            
            <!-- Liste des villes -->
            <div>
                <h3 class="font-semibold text-gray-700 mb-3">Répartition par Ville</h3>
                <div class="overflow-y-auto max-h-64">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ville</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de Clients</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                                $totalGeographic = $geographicDistribution->sum('total');
                            ?>
                            <?php $__empty_1 = true; $__currentLoopData = $geographicDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $geo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900"><?php echo e($geo->ville); ?></td>
                                <td class="px-4 py-2 text-sm text-gray-500"><?php echo e($geo->total); ?></td>
                                <td class="px-4 py-2 text-sm text-gray-500"><?php echo e($totalGeographic > 0 ? number_format(($geo->total / $totalGeographic) * 100, 1) : 0); ?>%</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-sm text-gray-500 text-center">Aucune donnée géographique disponible</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alertes -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Alertes</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php if($clientsAtRisk > 0): ?>
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Clients à risque</h3>
                        <div class="mt-1 text-sm text-yellow-700">
                            <p><?php echo e($clientsAtRisk); ?> clients n'ont pas eu d'activité depuis 3 mois.</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($inactiveClients > 0): ?>
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-slash text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Clients inactifs</h3>
                        <div class="mt-1 text-sm text-red-700">
                            <p><?php echo e($inactiveClients); ?> clients n'ont pas eu d'activité depuis 6 mois.</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\dashboard.blade.php ENDPATH**/ ?>