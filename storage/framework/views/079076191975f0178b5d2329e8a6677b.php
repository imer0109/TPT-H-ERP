

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Fournisseurs</h1>
        <div class="text-sm text-gray-500">
            Mis à jour le <?php echo e(now()->format('d/m/Y à H:i')); ?>

        </div>
    </div>

    <!-- Alertes importantes -->
    <?php if($overdueInvoices > 0 || $openIssues > 0): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Attention requise</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <?php if($overdueInvoices > 0): ?>
                            <p><?php echo e($overdueInvoices); ?> facture(s) en retard (<?php echo e(number_format($overdueAmount, 0, ',', ' ')); ?> XAF)</p>
                        <?php endif; ?>
                        <?php if($openIssues > 0): ?>
                            <p><?php echo e($openIssues); ?> réclamation(s) ouverte(s)</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Fournisseurs -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Fournisseurs</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($totalSuppliers); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e($activeSuppliers); ?> actifs</p>
                </div>
            </div>
        </div>

        <!-- Commandes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Commandes</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($totalOrders); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e($pendingOrders); ?> en attente</p>
                </div>
            </div>
        </div>

        <!-- Paiements -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Paiements</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e(number_format($totalPayments, 0, ',', ' ')); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e(number_format($paymentsThisMonth, 0, ',', ' ')); ?> ce mois</p>
                </div>
            </div>
        </div>

        <!-- Réclamations -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Réclamations</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($totalIssues); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e($openIssues); ?> ouvertes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Répartition par type d'activité -->
    <div class="mt-6 bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Répartition par type d'activité</h3>
        </div>
        <div class="p-6">
            <?php if($suppliersByActivity->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php $__currentLoopData = $suppliersByActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-900">
                                    <?php echo e($activity->activite); ?>

                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?php echo e($activity->count); ?>

                                </span>
                            </div>
                            <div class="mt-2 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" 
                                     style="width: <?php echo e(($activity->count / $totalSuppliers) * 100); ?>%">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Top fournisseurs -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Top fournisseurs par volume</h3>
            </div>
            <div class="p-6">
                <?php if($topSuppliers->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $topSuppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($supplier->fournisseur->raison_sociale); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($supplier->order_count); ?> commande(s)</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900"><?php echo e(number_format($supplier->total_amount, 0, ',', ' ')); ?> XAF</p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-4">Aucune donnée disponible...</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Fournisseurs à risque -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Fournisseurs à risque</h3>
            </div>
            <div class="p-6">
                <?php if($riskySuppliers->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $riskySuppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($supplier->raison_sociale); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($supplier->supplier_orders_count); ?> commande(s), <?php echo e($supplier->supplier_issues_count); ?> réclamation(s)</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Risque
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-4">Aucun fournisseur à risque</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Fournisseurs les mieux notés -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Fournisseurs les mieux notés</h3>
            </div>
            <div class="p-6">
                <?php if($topRatedSuppliers->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $topRatedSuppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($supplier->raison_sociale); ?></p>
                                    <div class="flex">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= round($supplier->supplier_ratings_avg_overall_score)): ?>
                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            <?php else: ?>
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span class="text-xs text-gray-500 ml-1">(<?php echo e(number_format($supplier->supplier_ratings_avg_overall_score, 1)); ?>/5)</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($supplier->supplier_ratings_count); ?> évaluation(s)</p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-4">Aucune évaluation disponible</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Évolution des dépenses -->
    <?php if($monthlyExpenses->count() > 0): ?>
        <div class="mt-6 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Évolution des dépenses (6 derniers mois)</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <?php $__currentLoopData = $monthlyExpenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600"><?php echo e(\Carbon\Carbon::createFromFormat('Y-m', $expense->month)->format('M Y')); ?></span>
                                    <span class="font-medium"><?php echo e(number_format($expense->total, 0, ',', ' ')); ?> XAF</span>
                                </div>
                                <div class="mt-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e(($expense->total / $monthlyExpenses->max('total')) * 100); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Contrats expirant bientôt -->
    <?php if($expiringContracts->count() > 0): ?>
        <div class="mt-6 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Contrats expirant bientôt</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php $__currentLoopData = $expiringContracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-3 border rounded-lg <?php echo e($contract->days_until_expiry <= 7 ? 'border-red-300 bg-red-50' : 'border-yellow-300 bg-yellow-50'); ?>">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="<?php echo e(route('fournisseurs.contracts.show', $contract)); ?>" class="text-blue-600 hover:text-blue-800">
                                        <?php echo e($contract->contract_number); ?>

                                    </a>
                                </p>
                                <p class="text-xs text-gray-500">
                                    <?php echo e($contract->fournisseur->raison_sociale); ?>

                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    <?php echo e($contract->end_date->format('d/m/Y')); ?>

                                </p>
                                <p class="text-xs <?php echo e($contract->days_until_expiry <= 7 ? 'text-red-600' : 'text-yellow-600'); ?>">
                                    Dans <?php echo e($contract->days_until_expiry); ?> jours
                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Chart.js for enhanced visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // You can add more advanced chart visualizations here if needed
        console.log('Dashboard loaded');
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\dashboard.blade.php ENDPATH**/ ?>