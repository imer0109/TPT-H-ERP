

<?php $__env->startSection('title', 'Tableau de bord Agent Opérationnel'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de bord Agent Opérationnel</h1>
        <p class="text-gray-600 mt-2">Vue d'ensemble des tâches et activités</p>
    </div>

    <!-- Task Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-medium">Tâches Totales</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($data['totalTasks']); ?></p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-medium">Tâches Complétées</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($data['completedTasks']); ?></p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm font-medium">Tâches En Attente</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($data['pendingTasks']); ?></p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
            <h3 class="text-gray-500 text-sm font-medium">Affectées Aujourd'hui</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($data['assignedToday']); ?></p>
        </div>
    </div>

    <!-- Performance and Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Performance</h3>
            <div class="flex items-center justify-center">
                <div class="relative w-48 h-48">
                    <svg class="w-full h-full" viewBox="0 0 100 100">
                        <!-- Background circle -->
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#e5e7eb" stroke-width="8"></circle>
                        <!-- Progress circle -->
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#10b981" stroke-width="8" 
                                stroke-dasharray="283" 
                                stroke-dashoffset="<?php echo e(283 - (283 * $data['performanceScore']) / 100); ?>"
                                transform="rotate(-90 50 50)" 
                                stroke-linecap="round"></circle>
                        <!-- Center text -->
                        <text x="50" y="50" text-anchor="middle" dy=".3em" font-size="16" fill="#4b5563"><?php echo e($data['performanceScore']); ?>%</text>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-center">
                <p class="text-gray-600">Score de Performance</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Activités Récentes</h3>
            <div class="space-y-4">
                <?php $__currentLoopData = $data['recentActivities']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-start">
                    <div class="mr-3">
                        <?php if($activity['status'] == 'completed'): ?>
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        <?php elseif($activity['status'] == 'in_progress'): ?>
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        <?php else: ?>
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800"><?php echo e($activity['activity']); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($activity['date']); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Upcoming Tasks -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Prochaines Tâches</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tâche</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Échéance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $data['upcomingTasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($task['task']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($task['priority'] == 'high'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Haute
                                </span>
                            <?php elseif($task['priority'] == 'medium'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Moyenne
                                </span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Basse
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($task['deadline']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Démarrer</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions Rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?php echo e(route('hr.employees.index') ?? '#'); ?>" class="border border-gray-200 rounded-lg p-4 text-center hover:border-blue-300 hover:shadow-md transition">
                <div class="mx-auto bg-blue-100 p-2 rounded-full w-12 h-12 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Employés</span>
            </a>
            
            <a href="<?php echo e(route('clients.index') ?? '#'); ?>" class="border border-gray-200 rounded-lg p-4 text-center hover:border-blue-300 hover:shadow-md transition">
                <div class="mx-auto bg-green-100 p-2 rounded-full w-12 h-12 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Clients</span>
            </a>
            
            <a href="<?php echo e(route('fournisseurs.index') ?? '#'); ?>" class="border border-gray-200 rounded-lg p-4 text-center hover:border-blue-300 hover:shadow-md transition">
                <div class="mx-auto bg-purple-100 p-2 rounded-full w-12 h-12 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Fournisseurs</span>
            </a>
            
            <a href="<?php echo e(route('stock.products.index') ?? '#'); ?>" class="border border-gray-200 rounded-lg p-4 text-center hover:border-blue-300 hover:shadow-md transition">
                <div class="mx-auto bg-yellow-100 p-2 rounded-full w-12 h-12 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Stock</span>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\dashboards\operational.blade.php ENDPATH**/ ?>