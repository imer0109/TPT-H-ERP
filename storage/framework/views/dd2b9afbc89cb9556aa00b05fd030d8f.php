

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête du tableau de bord -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord Achats</h1>
                <p class="text-gray-600 mt-1">Vue d'ensemble des activités d'achat</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('purchases.requests.create')); ?>" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Nouvelle Demande
                </a>
                <a href="<?php echo e(route('purchases.orders.create')); ?>" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-file-invoice mr-2"></i>Nouveau BOC
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Demandes d'achat</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_requests'] ?? 0); ?></p>
                    <p class="text-sm text-gray-600"><?php echo e($stats['pending_requests'] ?? 0); ?> en attente</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-invoice text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Bons de commande</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_orders'] ?? 0); ?></p>
                    <p class="text-sm text-gray-600"><?php echo e($stats['confirmed_orders'] ?? 0); ?> confirmés</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-truck text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Livraisons</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['delivered_orders'] ?? 0); ?></p>
                    <p class="text-sm text-gray-600">Ce mois</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-euro-sign text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Montant total</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['total_amount'] ?? 0, 0, ',', ' ')); ?></p>
                    <p class="text-sm text-gray-600"><?php echo e(number_format($stats['monthly_amount'] ?? 0, 0, ',', ' ')); ?> ce mois</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et analyses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Évolution mensuelle -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Évolution mensuelle des achats</h2>
            <div class="h-64">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Répartition par nature -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Répartition Biens vs Services</h2>
            <div class="h-64">
                <canvas id="natureChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Commandes récentes et Top fournisseurs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Commandes récentes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Commandes récentes</h2>
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $recent_orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-file-invoice text-purple-600 text-sm"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900"><?php echo e($order->code); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($order->fournisseur->nom); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900"><?php echo e(number_format($order->montant_ttc, 0, ',', ' ')); ?> FCFA</p>
                            <p class="text-xs text-gray-500"><?php echo e($order->created_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-500 text-center py-4">Aucune commande récente</p>
                <?php endif; ?>
            </div>
            <div class="mt-4">
                <a href="<?php echo e(route('purchases.orders.index')); ?>" 
                   class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                    Voir toutes les commandes →
                </a>
            </div>
        </div>

        <!-- Top fournisseurs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Top fournisseurs</h2>
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $top_suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-building text-green-600 text-sm"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900"><?php echo e($supplier->fournisseur->nom); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($supplier->order_count); ?> commande(s)</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900"><?php echo e(number_format($supplier->total_amount, 0, ',', ' ')); ?> FCFA</p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-500 text-center py-4">Aucun fournisseur</p>
                <?php endif; ?>
            </div>
            <div class="mt-4">
                <a href="<?php echo e(route('fournisseurs.index')); ?>" 
                   class="text-green-600 hover:text-green-800 text-sm font-medium">
                    Voir tous les fournisseurs →
                </a>
            </div>
        </div>
    </div>

    <!-- Alertes et notifications -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Alertes et notifications</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Demandes en attente de validation -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">Demandes en attente</p>
                        <p class="text-2xl font-bold text-yellow-900"><?php echo e($alerts['pending_requests'] ?? 0); ?></p>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="<?php echo e(route('purchases.requests.index', ['statut' => 'En attente'])); ?>" 
                       class="text-yellow-700 hover:text-yellow-900 text-sm font-medium">
                        Voir les demandes →
                    </a>
                </div>
            </div>

            <!-- Commandes en retard -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-clock text-red-600 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-red-800">Commandes en retard</p>
                        <p class="text-2xl font-bold text-red-900"><?php echo e($alerts['overdue_orders'] ?? 0); ?></p>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="<?php echo e(route('purchases.orders.index')); ?>" 
                       class="text-red-700 hover:text-red-900 text-sm font-medium">
                        Voir les commandes →
                    </a>
                </div>
            </div>

            <!-- Budget mensuel -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Budget mensuel</p>
                        <p class="text-lg font-bold text-blue-900">
                            <?php echo e(number_format(($stats['monthly_amount'] ?? 0) / 1000000, 1)); ?>M / <?php echo e(number_format(($budget['monthly_limit'] ?? 10000000) / 1000000, 1)); ?>M
                        </p>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="w-full bg-blue-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" 
                             style="width: <?php echo e(min(100, (($stats['monthly_amount'] ?? 0) / ($budget['monthly_limit'] ?? 10000000)) * 100)); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Actions rapides</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?php echo e(route('purchases.requests.create')); ?>" 
               class="flex flex-col items-center p-4 bg-red-50 hover:bg-red-100 rounded-lg transition">
                <i class="fas fa-plus text-red-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-red-800">Nouvelle DA</span>
            </a>
            <a href="<?php echo e(route('purchases.orders.create')); ?>" 
               class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                <i class="fas fa-file-invoice text-purple-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-purple-800">Nouveau BOC</span>
            </a>
            <a href="<?php echo e(route('fournisseurs.create')); ?>" 
               class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                <i class="fas fa-building text-green-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-green-800">Nouveau Fournisseur</span>
            </a>
            <a href="<?php echo e(route('purchases.analytics')); ?>" 
               class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                <i class="fas fa-chart-bar text-blue-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-blue-800">Analyses</span>
            </a>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'évolution mensuelle
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chartData['monthly']['labels'] ?? []); ?>,
            datasets: [{
                label: 'Montant des achats',
                data: <?php echo json_encode($chartData['monthly']['data'] ?? []); ?>,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                        }
                    }
                }
            }
        }
    });

    // Graphique de répartition par nature
    const natureCtx = document.getElementById('natureChart').getContext('2d');
    new Chart(natureCtx, {
        type: 'doughnut',
        data: {
            labels: ['Biens', 'Services'],
            datasets: [{
                data: <?php echo json_encode($chartData['nature'] ?? [0, 0]); ?>,
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(147, 51, 234)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\purchases\dashboard.blade.php ENDPATH**/ ?>