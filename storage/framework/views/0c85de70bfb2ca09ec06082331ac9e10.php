<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Tableau de Bord Administrateur</h1>
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 text-sm text-red-700 bg-red-100 rounded-full">Admin Système</span>
            <span class="text-gray-600">Bienvenue, <?php echo e(Auth::user()->prenom); ?> <?php echo e(Auth::user()->nom); ?></span>
        </div>
    </div>

    <!-- System Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Utilisateurs Totaux</h3>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e(\App\Models\User::count()); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Système</h3>
                    <p class="text-2xl font-semibold text-gray-900">Opérationnel</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Modules Actifs</h3>
                    <p class="text-2xl font-semibold text-gray-900">8</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Alertes Sécurité</h3>
                    <p class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Distribution Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Répartition des Utilisateurs</h3>
            <div class="h-64">
                <canvas id="userDistributionChart"></canvas>
            </div>
        </div>

        <!-- System Activity Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Activité Système (24h)</h3>
            <div class="h-64">
                <canvas id="systemActivityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Logs -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Journaux Système Récents</h3>
        </div>
        <div class="divide-y divide-gray-200">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                        <p class="text-sm text-gray-900">Sauvegarde automatique effectuée</p>
                    </div>
                    <span class="text-xs text-gray-500"><?php echo e(now()->subMinutes(15)->format('d/m/Y H:i')); ?></span>
                </div>
            </div>
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                        <p class="text-sm text-gray-900">Nouvel utilisateur créé (fournisseur@tpt-h.com)</p>
                    </div>
                    <span class="text-xs text-gray-500"><?php echo e(now()->subHours(1)->format('d/m/Y H:i')); ?></span>
                </div>
            </div>
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="w-2 h-2 bg-gray-500 rounded-full mr-3"></span>
                        <p class="text-sm text-gray-900">Mise à jour des permissions</p>
                    </div>
                    <span class="text-xs text-gray-500"><?php echo e(now()->subHours(3)->format('d/m/Y H:i')); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User Distribution Chart
        const userCtx = document.getElementById('userDistributionChart').getContext('2d');
        new Chart(userCtx, {
            type: 'pie',
            data: {
                labels: ['RH', 'Compta', 'Achats', 'Ops', 'Autres'],
                datasets: [{
                    data: [15, 10, 8, 25, 42],
                    backgroundColor: ['#F59E0B', '#10B981', '#3B82F6', '#6366F1', '#9CA3AF'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // System Activity Chart
        const activityCtx = document.getElementById('systemActivityChart').getContext('2d');
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
                datasets: [{
                    label: 'Requêtes / min',
                    data: [12, 8, 45, 120, 150, 85],
                    borderColor: '#4F46E5',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(79, 70, 229, 0.1)'
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
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\dashboards\admin.blade.php ENDPATH**/ ?>