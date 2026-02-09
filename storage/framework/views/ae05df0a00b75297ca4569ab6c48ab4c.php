

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><?php echo e($agency->nom); ?></h1>
            <p class="text-gray-600">Agence de <?php echo e($agency->company->raison_sociale); ?></p>
        </div>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('audit-trails.agency', $agency->id)); ?>" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                Historique
            </a>
            <a href="<?php echo e(route('agencies.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Agency Info -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Informations Générales</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Nom:</span> <?php echo e($agency->nom); ?></p>
                    <p><span class="font-medium">Code unique:</span> <?php echo e($agency->code_unique); ?></p>
                    <p><span class="font-medium">Société:</span> 
                        <a href="<?php echo e(route('companies.dashboard.company', $agency->company->id)); ?>" class="text-blue-600 hover:text-blue-800">
                            <?php echo e($agency->company->raison_sociale); ?>

                        </a>
                    </p>
                    <p><span class="font-medium">Zone géographique:</span> <?php echo e($agency->zone_geographique); ?></p>
                    <p><span class="font-medium">Statut:</span> 
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php echo e($agency->statut === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                            <?php echo e($agency->statut === 'active' ? 'Active' : 'En veille'); ?>

                        </span>
                    </p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Responsable</h3>
                <div class="space-y-2">
                    <?php if($agency->responsable): ?>
                        <p><span class="font-medium">Nom:</span> <?php echo e($agency->responsable->name); ?></p>
                        <p><span class="font-medium">Email:</span> <?php echo e($agency->responsable->email); ?></p>
                    <?php else: ?>
                        <p class="text-gray-500">Aucun responsable assigné</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Coordonnées</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Adresse:</span> <?php echo e($agency->adresse); ?></p>
                    <?php if($agency->latitude && $agency->longitude): ?>
                        <p><span class="font-medium">Coordonnées GPS:</span> <?php echo e($agency->latitude); ?>, <?php echo e($agency->longitude); ?></p>
                        <div class="mt-2">
                            <a href="https://www.google.com/maps?q=<?php echo e($agency->latitude); ?>,<?php echo e($agency->longitude); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                Voir sur la carte
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Résumé Financier</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Solde Total des Caisses</p>
                        <p class="text-xl font-bold"><?php echo e(number_format($totalBalance, 2, ',', ' ')); ?> <?php echo e($agency->company->devise); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Nombre de Caisses</p>
                        <p class="text-xl font-bold"><?php echo e($cashRegisters->count()); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Encaissements</p>
                        <p class="text-xl font-bold"><?php echo e(number_format($encaissements, 2, ',', ' ')); ?> <?php echo e($agency->company->devise); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-indigo-100 text-indigo-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Flux de Trésorerie Net</p>
                        <p class="text-xl font-bold"><?php echo e(number_format($netCashFlow, 2, ',', ' ')); ?> <?php echo e($agency->company->devise); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-pink-100 text-pink-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Nombre de Transactions</p>
                        <p class="text-xl font-bold"><?php echo e($transactionCount); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-teal-100 text-teal-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Décaissements</p>
                        <p class="text-xl font-bold"><?php echo e(number_format($decaissements, 2, ',', ' ')); ?> <?php echo e($agency->company->devise); ?></p>
                    </div>
                </div>
            </div>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Détails</th>
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
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?php if($activity->description): ?>
                                        <?php echo e($activity->description); ?>

                                    <?php else: ?>
                                        <?php if(is_array($activity->changes)): ?>
                                            <?php $__currentLoopData = $activity->changes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="font-medium"><?php echo e($field); ?>:</span> <?php echo e($value); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-4 text-gray-500">
                <p>Aucune activité récente pour cette agence.</p>
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

    <!-- Entity-Specific Parameters -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Bank Accounts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Comptes Bancaires</h2>
                <a href="<?php echo e(route('bank-accounts.index')); ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                    Voir tout
                </a>
            </div>
            <?php if($bankAccounts->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border rounded-lg p-3">
                            <p class="font-medium text-gray-900"><?php echo e($account->bank_name); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e($account->account_number); ?></p>
                            <p class="text-sm text-gray-500"><?php echo e($account->account_type); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-gray-500">
                    <p>Aucun compte bancaire enregistré.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Policies -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Politiques Internes</h2>
                <a href="<?php echo e(route('policies.index')); ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                    Voir tout
                </a>
            </div>
            <?php if($policies->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $policies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $policy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border rounded-lg p-3">
                            <p class="font-medium text-gray-900"><?php echo e($policy->title); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e(Str::limit($policy->description, 50)); ?></p>
                            <p class="text-xs text-gray-500">Mis à jour le <?php echo e($policy->updated_at->format('d/m/Y')); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-gray-500">
                    <p>Aucune politique enregistrée.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Tax Regulations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Réglementations Fiscales</h2>
                <a href="<?php echo e(route('tax-regulations.index')); ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                    Voir tout
                </a>
            </div>
            <?php if($taxRegulations->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $taxRegulations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $regulation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border rounded-lg p-3">
                            <p class="font-medium text-gray-900"><?php echo e($regulation->name); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e(Str::limit($regulation->description, 50)); ?></p>
                            <p class="text-xs text-gray-500">Taux: <?php echo e($regulation->tax_rate); ?>%</p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-gray-500">
                    <p>Aucune réglementation fiscale enregistrée.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Financial Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Transactions Récentes</h2>
            <?php if($recentTransactions->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caisse</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500"><?php echo e($transaction->cashRegister->name); ?></td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo e($transaction->type === 'encaissement' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo e($transaction->type === 'encaissement' ? 'Encaissement' : 'Décaissement'); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e(number_format($transaction->montant, 2, ',', ' ')); ?> <?php echo e($transaction->cashRegister->currency); ?></td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500"><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-gray-500">
                    <p>Aucune transaction récente.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Cash Flow Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Flux de Trésorerie</h2>
            <div class="h-64">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Cash Flow Chart
    const cashFlowCtx = document.getElementById('cashFlowChart').getContext('2d');
    const cashFlowChart = new Chart(cashFlowCtx, {
        type: 'bar',
        data: {
            labels: ['Encaissements', 'Décaissements', 'Flux Net'],
            datasets: [{
                label: 'Montant (<?php echo e($agency->company->devise); ?>)',
                data: [<?php echo e($encaissements); ?>, <?php echo e($decaissements); ?>, <?php echo e($netCashFlow); ?>],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\companies\dashboard\agency.blade.php ENDPATH**/ ?>