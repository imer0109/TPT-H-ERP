

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de bord des Entités</h1>
        <a href="<?php echo e(route('audit-trails.index')); ?>" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
            Historique Global
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Sociétés Actives</p>
                    <p class="text-2xl font-bold"><?php echo e($activeCompanies); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Sociétés Inactives</p>
                    <p class="text-2xl font-bold"><?php echo e($inactiveCompanies); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Agences Actives</p>
                    <p class="text-2xl font-bold"><?php echo e($activeAgencies); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100 text-gray-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Agences en Veille</p>
                    <p class="text-2xl font-bold"><?php echo e($standbyAgencies); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Holdings and Subsidiaries -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Arborescence des Entités</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Société</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Secteur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pays</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filiale(s)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agence(s)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $holdings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $holding): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="bg-blue-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <?php if($holding->logo): ?>
                                        <img src="<?php echo e(Storage::url($holding->logo)); ?>" class="h-8 w-8 rounded-full mr-3 object-cover">
                                    <?php else: ?>
                                        <div class="h-8 w-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center text-gray-500">N/A</div>
                                    <?php endif; ?>
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="<?php echo e(route('companies.dashboard.company', $holding->id)); ?>" class="text-blue-600 hover:text-blue-900">
                                            <?php echo e($holding->raison_sociale); ?>

                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Holding
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($holding->secteur_activite); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($holding->pays); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($holding->filiales->count()); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($holding->agencies->count()); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($holding->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($holding->active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>
                        </tr>
                        
                        <?php $__currentLoopData = $holding->filiales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filiale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center ml-8">
                                        <?php if($filiale->logo): ?>
                                            <img src="<?php echo e(Storage::url($filiale->logo)); ?>" class="h-8 w-8 rounded-full mr-3 object-cover">
                                        <?php else: ?>
                                            <div class="h-8 w-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center text-gray-500">N/A</div>
                                        <?php endif; ?>
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="<?php echo e(route('companies.dashboard.company', $filiale->id)); ?>" class="text-blue-600 hover:text-blue-900">
                                                <?php echo e($filiale->raison_sociale); ?>

                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Filiale
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($filiale->secteur_activite); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($filiale->pays); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($filiale->agencies->count()); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($filiale->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e($filiale->active ? 'Active' : 'Inactive'); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Activités Récentes</h2>
        <?php if($recentActivities->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entité</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($activity->created_at->format('d/m/Y H:i')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($activity->user ? $activity->user->name : 'Système'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?php echo e(ucfirst($activity->action)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php if($activity->entity): ?>
                                        <?php if($activity->entity_type === 'company'): ?>
                                            <a href="<?php echo e(route('companies.dashboard.company', $activity->entity->id)); ?>" class="text-blue-600 hover:text-blue-900">
                                                <?php echo e($activity->entity->raison_sociale); ?>

                                            </a>
                                        <?php elseif($activity->entity_type === 'agency'): ?>
                                            <a href="<?php echo e(route('companies.dashboard.agency', $activity->entity->id)); ?>" class="text-blue-600 hover:text-blue-900">
                                                <?php echo e($activity->entity->nom); ?>

                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Entité supprimée
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-4 text-gray-500">
                <p>Aucune activité récente.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Alerts -->
    <?php if(count($alerts) > 0): ?>
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Alertes & Notifications</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 rounded-lg border 
                        <?php if($alert['type'] === 'danger'): ?> bg-red-50 border-red-200
                        <?php elseif($alert['type'] === 'warning'): ?> bg-yellow-50 border-yellow-200
                        <?php elseif($alert['type'] === 'info'): ?> bg-blue-50 border-blue-200
                        <?php endif; ?>">
                        <div class="flex items-start">
                            <i class="<?php echo e($alert['icon']); ?> 
                                <?php if($alert['type'] === 'danger'): ?> text-red-600
                                <?php elseif($alert['type'] === 'warning'): ?> text-yellow-600
                                <?php elseif($alert['type'] === 'info'): ?> text-blue-600
                                <?php endif; ?> mr-3 mt-1"></i>
                            <div>
                                <h3 class="text-sm font-medium 
                                    <?php if($alert['type'] === 'danger'): ?> text-red-800
                                    <?php elseif($alert['type'] === 'warning'): ?> text-yellow-800
                                    <?php elseif($alert['type'] === 'info'): ?> text-blue-800
                                    <?php endif; ?>">
                                    <?php echo e($alert['message']); ?>

                                </h3>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Comparative Chart for Subsidiaries -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Comparatif des Filiales</h2>
            <div class="h-64">
                <canvas id="comparativeChart"></canvas>
            </div>
        </div>
        
        <!-- Companies by Sector -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Sociétés par Secteur d'Activité</h2>
            <div class="h-64">
                <canvas id="sectorChart"></canvas>
            </div>
        </div>

        <!-- Companies by Country -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Sociétés par Pays</h2>
            <div class="h-64">
                <canvas id="countryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sector Chart
    const sectorCtx = document.getElementById('sectorChart').getContext('2d');
    const sectorChart = new Chart(sectorCtx, {
        type: 'pie',
        data: {
            labels: [<?php $__currentLoopData = $companiesBySector; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sector): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>'<?php echo e($sector->secteur_activite); ?>',<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
            datasets: [{
                data: [<?php $__currentLoopData = $companiesBySector; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sector): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($sector->count); ?>,<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
                backgroundColor: [
                    '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Country Chart
    const countryCtx = document.getElementById('countryChart').getContext('2d');
    const countryChart = new Chart(countryCtx, {
        type: 'bar',
        data: {
            labels: [<?php $__currentLoopData = $companiesByCountry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>'<?php echo e($country->pays); ?>',<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
            datasets: [{
                label: 'Nombre de sociétés',
                data: [<?php $__currentLoopData = $companiesByCountry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($country->count); ?>,<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
                backgroundColor: '#3B82F6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Comparative Chart for Subsidiaries
    const comparativeCtx = document.getElementById('comparativeChart').getContext('2d');
    const comparativeChart = new Chart(comparativeCtx, {
        type: 'bar',
        data: {
            labels: [<?php $__currentLoopData = $holdings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $holding): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php $__currentLoopData = $holding->filiales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filiale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>'<?php echo e($filiale->raison_sociale); ?>',<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
            datasets: [{
                label: 'Nombre d\'agences',
                data: [<?php $__currentLoopData = $holdings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $holding): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php $__currentLoopData = $holding->filiales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filiale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($filiale->agencies->count()); ?>,<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\companies\dashboard\index.blade.php ENDPATH**/ ?>