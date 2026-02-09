

<?php $__env->startSection('title', 'Tableau de bord principal'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de bord principal</h1>
        <p class="text-gray-600 mt-2">Vue d'ensemble de l'ERP TPT-H</p>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-medium">Trésorerie consolidée</h3>
            <p class="text-2xl font-bold text-gray-800 mt-2"><?php echo e(number_format($tresorerieConsolidee, 0, '', ' ')); ?> FCFA</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-medium">Masse salariale</h3>
            <p class="text-2xl font-bold text-gray-800 mt-2"><?php echo e(number_format($masseSalariale, 0, '', ' ')); ?> FCFA</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm font-medium">Stock disponible</h3>
            <p class="text-2xl font-bold text-gray-800 mt-2"><?php echo e(number_format($stockDisponible, 0, '', ' ')); ?> unités</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
            <h3 class="text-gray-500 text-sm font-medium">Achats mensuels</h3>
            <p class="text-2xl font-bold text-gray-800 mt-2"><?php echo e(number_format($achatsMensuels, 0, '', ' ')); ?> FCFA</p>
        </div>
    </div>

    <!-- Alertes -->
    <?php if(isset($alerts) && count($alerts) > 0): ?>
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Alertes et notifications</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-4 rounded-lg border 
                    <?php if($alert['type'] === 'danger'): ?> bg-red-50 border-red-200
                    <?php elseif($alert['type'] === 'warning'): ?> bg-yellow-50 border-yellow-200
                    <?php elseif($alert['type'] === 'info'): ?> bg-blue-50 border-blue-200
                    <?php endif; ?>">
                    <div class="flex items-start">
                        <i class="<?php echo e($alert['icon'] ?? 'fas fa-exclamation-circle'); ?> 
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

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Performance financière</h3>
            <canvas id="financeChart" height="300"></canvas>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Activité par service</h3>
            <canvas id="activityChart" height="300"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Finance Chart
    const financeCtx = document.getElementById('financeChart').getContext('2d');
    new Chart(financeCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            datasets: [{
                label: 'Revenus',
                data: [1200000, 1900000, 1500000, 2100000, 1800000, 2400000],
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Dépenses',
                data: [900000, 1200000, 1100000, 1500000, 1300000, 1800000],
                borderColor: '#EF4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
        type: 'doughnut',
        data: {
            labels: ['Commercial', 'Services', 'Production', 'Administration'],
            datasets: [{
                data: [30, 25, 25, 20],
                backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\dashboard\index.blade.php ENDPATH**/ ?>