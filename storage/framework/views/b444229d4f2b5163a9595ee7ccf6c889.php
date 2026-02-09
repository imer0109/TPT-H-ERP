

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Trésorerie -->
        <?php if(auth()->user()->canAccessModule('cash')): ?>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Trésorerie consolidée</h3>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo e(number_format($tresorerieConsolidee ?? 0, 0, ',', ' ')); ?> FCFA</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Masse salariale -->
        <?php if(auth()->user()->canAccessModule('hr')): ?>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Masse salariale</h3>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo e(number_format($masseSalariale ?? 0, 0, ',', ' ')); ?> FCFA</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Stocks -->
        <?php if(auth()->user()->canAccessModule('inventory')): ?>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Stocks disponibles</h3>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo e($stockDisponible ?? 0); ?> articles</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Achats -->
        <?php if(auth()->user()->canAccessModule('purchases')): ?>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Achats mensuels</h3>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo e(number_format($achatsMensuels ?? 0, 0, ',', ' ')); ?> FCFA</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Line Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Encaissements/Dépenses</h3>
            <div class="h-64">
                <canvas id="revenueChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Répartition par pôle d'activité</h3>
            <div class="h-64">
                <canvas id="activityChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Alertes & Notifications</h3>
        </div>
        <div class="divide-y divide-gray-200">
            <?php $__empty_1 = true; $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="px-6 py-4 flex items-center">
                <span class="flex-shrink-0 w-2 h-2 rounded-full 
                    <?php echo e($alert['type'] == 'danger' ? 'bg-red-600' : ''); ?>

                    <?php echo e($alert['type'] == 'warning' ? 'bg-yellow-500' : ''); ?>

                    <?php echo e($alert['type'] == 'info' ? 'bg-blue-500' : ''); ?> mr-3"></span>
                <p class="text-sm text-gray-600"><?php echo e($alert['message']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="px-6 py-4 flex items-center">
                <span class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500 mr-3"></span>
                <p class="text-sm text-gray-600">Aucune alerte à afficher</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
        datasets: [{
            label: 'Encaissements',
            data: [1200000, 1900000, 1500000, 2100000, 1800000, 2400000],
            borderColor: '#C20000',
            backgroundColor: 'rgba(194,0,0,0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Dépenses',
            data: [900000, 1200000, 1100000, 1500000, 1300000, 1800000],
            borderColor: '#666',
            backgroundColor: 'rgba(102,102,102,0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
            legend: { position: 'bottom' }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Activity Chart
const activityCtx = document.getElementById('activityChart').getContext('2d');
new Chart(activityCtx, {
    type: 'pie',
    data: {
        labels: ['Commercial', 'Services', 'Production', 'Administration'],
        datasets: [{
            data: [30, 25, 25, 20],
            backgroundColor: ['#C20000', '#FF6B6B', '#4A90E2', '#50E3C2']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\dashboard.blade.php ENDPATH**/ ?>