

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête du tableau de bord -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord Comptable</h1>
                <p class="text-gray-600 mt-1">Vue d'ensemble des activités comptables</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('accounting.entries.create')); ?>" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Nouvelle Écriture
                </a>
                <a href="<?php echo e(route('accounting.chart-of-accounts.create')); ?>" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-sitemap mr-2"></i>Nouveau Compte
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
                        <i class="fas fa-file-invoice text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Écritures Comptables</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['total_entries'])); ?></p>
                    <p class="text-sm text-gray-600"><?php echo e($stats['pending_entries']); ?> en attente</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Écritures Validées</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['validated_entries'])); ?></p>
                    <p class="text-sm text-gray-600">Ce mois: <?php echo e($stats['monthly_entries']); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-plus-circle text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Débits</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['total_debit'], 0, ',', ' ')); ?></p>
                    <p class="text-sm text-gray-600"><?php echo e(number_format($stats['monthly_debit'], 0, ',', ' ')); ?> ce mois</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-minus-circle text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Crédits</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['total_credit'], 0, ',', ' ')); ?></p>
                    <p class="text-sm text-gray-600"><?php echo e(number_format($stats['monthly_credit'], 0, ',', ' ')); ?> ce mois</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et analyses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Évolution mensuelle -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Évolution Mensuelle</h2>
            <div class="h-64">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Répartition par journaux -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Répartition par Journaux</h2>
            <div class="h-64">
                <canvas id="journalChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Journaux et activité récente -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- État des journaux -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">État des Journaux</h2>
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $journals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $journal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <?php switch($journal->journal_type):
                                        case ('caisse'): ?>
                                            <i class="fas fa-cash-register text-indigo-600 text-sm"></i>
                                            <?php break; ?>
                                        <?php case ('banque'): ?>
                                            <i class="fas fa-university text-indigo-600 text-sm"></i>
                                            <?php break; ?>
                                        <?php case ('achat'): ?>
                                            <i class="fas fa-shopping-cart text-indigo-600 text-sm"></i>
                                            <?php break; ?>
                                        <?php case ('vente'): ?>
                                            <i class="fas fa-shopping-bag text-indigo-600 text-sm"></i>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <i class="fas fa-book text-indigo-600 text-sm"></i>
                                    <?php endswitch; ?>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900"><?php echo e($journal->name); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($journal->total_entries); ?> écriture(s)</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <?php if($journal->pending_entries > 0): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <?php echo e($journal->pending_entries); ?> en attente
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    À jour
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-500 text-center py-4">Aucun journal configuré</p>
                <?php endif; ?>
            </div>
            <div class="mt-4">
                <a href="<?php echo e(route('accounting.journals.index')); ?>" 
                   class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                    Voir tous les journaux →
                </a>
            </div>
        </div>

        <!-- Écritures récentes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Écritures Récentes</h2>
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $recent_entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-file-invoice text-blue-600 text-sm"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900"><?php echo e($entry->entry_number); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e(Str::limit($entry->description, 30)); ?></p>
                                <p class="text-xs text-gray-400"><?php echo e($entry->journal->name); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900"><?php echo e(number_format($entry->debit_amount, 0, ',', ' ')); ?> FCFA</p>
                            <p class="text-xs text-gray-500"><?php echo e($entry->entry_date->format('d/m/Y')); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-500 text-center py-4">Aucune écriture récente</p>
                <?php endif; ?>
            </div>
            <div class="mt-4">
                <a href="<?php echo e(route('accounting.entries.index')); ?>" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Voir toutes les écritures →
                </a>
            </div>
        </div>
    </div>

    <!-- Alertes et notifications -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Alertes et Notifications</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Écritures en attente de validation -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">Validations en attente</p>
                        <p class="text-2xl font-bold text-yellow-900"><?php echo e($alerts['pending_validations']); ?></p>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="<?php echo e(route('accounting.entries.index', ['status' => 'brouillon'])); ?>" 
                       class="text-yellow-700 hover:text-yellow-900 text-sm font-medium">
                        Voir les écritures →
                    </a>
                </div>
            </div>

            <!-- Journaux déséquilibrés -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-balance-scale text-red-600 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-red-800">Journaux déséquilibrés</p>
                        <p class="text-2xl font-bold text-red-900"><?php echo e(count($alerts['unbalanced_journals'])); ?></p>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="<?php echo e(route('accounting.balance')); ?>" 
                       class="text-red-700 hover:text-red-900 text-sm font-medium">
                        Vérifier la balance →
                    </a>
                </div>
            </div>

            <!-- Soldes négatifs anormaux -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-minus-circle text-orange-600 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-orange-800">Soldes négatifs</p>
                        <p class="text-2xl font-bold text-orange-900"><?php echo e(count($alerts['negative_balances'])); ?></p>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="<?php echo e(route('accounting.general-ledger')); ?>" 
                       class="text-orange-700 hover:text-orange-900 text-sm font-medium">
                        Consulter le grand livre →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Actions Rapides</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?php echo e(route('accounting.entries.create')); ?>" 
               class="flex flex-col items-center p-4 bg-red-50 hover:bg-red-100 rounded-lg transition">
                <i class="fas fa-plus text-red-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-red-800">Nouvelle Écriture</span>
            </a>
            <a href="<?php echo e(route('accounting.balance')); ?>" 
               class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                <i class="fas fa-balance-scale text-blue-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-blue-800">Balance Générale</span>
            </a>
            <a href="<?php echo e(route('accounting.chart-of-accounts.index')); ?>" 
               class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                <i class="fas fa-sitemap text-green-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-green-800">Plan Comptable</span>
            </a>
            <a href="<?php echo e(route('accounting.reports.index')); ?>" 
               class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                <i class="fas fa-chart-bar text-purple-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-purple-800">États Financiers</span>
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
            labels: <?php echo json_encode($chartData['monthly_evolution']['labels'] ?? []); ?>,
            datasets: [{
                label: 'Montant des écritures',
                data: <?php echo json_encode($chartData['monthly_evolution']['data'] ?? []); ?>,
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

    // Graphique de répartition par journaux
    const journalCtx = document.getElementById('journalChart').getContext('2d');
    new Chart(journalCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($chartData['journal_distribution']['labels'] ?? []); ?>,
            datasets: [{
                data: <?php echo json_encode($chartData['journal_distribution']['data'] ?? []); ?>,
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)',
                    'rgb(139, 92, 246)',
                    'rgb(236, 72, 153)'
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\dashboard.blade.php ENDPATH**/ ?>