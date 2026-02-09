

<?php $__env->startSection('title', 'Tableau de bord Achats'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de bord Achats</h1>
        <p class="text-gray-600 mt-2">Vue d'ensemble des activités d'achat</p>
    </div>

    <!-- Purchases Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-medium">Demandes d'achat</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($totalRequests); ?></p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e($pendingRequests); ?> en attente</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
            <h3 class="text-gray-500 text-sm font-medium">Bons de commande</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($totalOrders); ?></p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e($completedOrders); ?> complétés</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-medium">Fournisseurs</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($totalSuppliers); ?></p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e($activeSuppliers); ?> actifs</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm font-medium">Articles Stock Bas</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($lowStockItems); ?></p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Évolution des Demandes et Commandes</h3>
            <div class="h-64">
                <canvas id="purchasesChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Récent</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded">
                    <span class="font-medium">Demandes récentes</span>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm"><?php echo e(count($recentRequests)); ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded">
                    <span class="font-medium">Commandes récentes</span>
                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-sm"><?php echo e(count($recentOrders)); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Requests -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Demandes Récentes</h3>
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $recentRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition">
                    <div>
                        <p class="font-medium text-gray-800"><?php echo e($request->reference); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($request->created_at->format('d M Y')); ?></p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                        <?php echo e($request->statut); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-sm text-gray-500 py-4">Aucune demande récente</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Commandes Récentes</h3>
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition">
                    <div>
                        <p class="font-medium text-gray-800"><?php echo e($order->reference); ?></p>
                        <p class="text-xs text-gray-500">Fournisseur</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                        <?php echo e($order->statut); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-sm text-gray-500 py-4">Aucune commande récente</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Suppliers -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Fournisseurs</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium">Total</span>
                    <span class="font-medium"><?php echo e($totalSuppliers); ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded">
                    <span class="font-medium">Actifs</span>
                    <span class="font-medium"><?php echo e($activeSuppliers); ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-yellow-50 rounded">
                    <span class="font-medium">En attente</span>
                    <span class="font-medium"><?php echo e($totalSuppliers - $activeSuppliers); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('purchasesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($monthlyStats['labels']); ?>,
                datasets: [
                    {
                        label: 'Demandes',
                        data: <?php echo json_encode($monthlyStats['requests']); ?>,
                        borderColor: 'rgb(139, 92, 246)',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Commandes',
                        data: <?php echo json_encode($monthlyStats['orders']); ?>,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\dashboards\purchases.blade.php ENDPATH**/ ?>