

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Scoring Client: <?php echo e($client->nom_raison_sociale); ?></h1>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('clients.show', $client)); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la fiche client
            </a>
        </div>
    </div>

    <!-- Scores -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Score de risque -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Score de Risque</h2>
                <div class="text-2xl font-bold <?php echo e($riskScore >= 70 ? 'text-green-600' : ($riskScore >= 40 ? 'text-yellow-600' : 'text-red-600')); ?>">
                    <?php echo e($riskScore); ?>/100
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-<?php echo e($riskScore >= 70 ? 'green' : ($riskScore >= 40 ? 'yellow' : 'red')); ?>-600 h-4 rounded-full" style="width: <?php echo e($riskScore); ?>%"></div>
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <?php if($riskScore >= 70): ?>
                    <span class="text-green-600">Faible risque</span> - Client fiable
                <?php elseif($riskScore >= 40): ?>
                    <span class="text-yellow-600">Risque modéré</span> - Surveillance recommandée
                <?php else: ?>
                    <span class="text-red-600">Risque élevé</span> - Attention requise
                <?php endif; ?>
            </div>
        </div>

        <!-- Score de solvabilité -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Score de Solvabilité</h2>
                <div class="text-2xl font-bold <?php echo e($solvencyScore >= 70 ? 'text-green-600' : ($solvencyScore >= 40 ? 'text-yellow-600' : 'text-red-600')); ?>">
                    <?php echo e($solvencyScore); ?>/100
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-<?php echo e($solvencyScore >= 70 ? 'green' : ($solvencyScore >= 40 ? 'yellow' : 'red')); ?>-600 h-4 rounded-full" style="width: <?php echo e($solvencyScore); ?>%"></div>
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <?php if($solvencyScore >= 70): ?>
                    <span class="text-green-600">Bonne solvabilité</span> - Client fiable
                <?php elseif($solvencyScore >= 40): ?>
                    <span class="text-yellow-600">Solvabilité modérée</span> - Conditions possibles
                <?php else: ?>
                    <span class="text-red-600">Faible solvabilité</span> - Précautions nécessaires
                <?php endif; ?>
            </div>
        </div>

        <!-- Score de satisfaction -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Score de Satisfaction</h2>
                <div class="text-2xl font-bold <?php echo e($satisfactionScore >= 70 ? 'text-green-600' : ($satisfactionScore >= 40 ? 'text-yellow-600' : 'text-red-600')); ?>">
                    <?php echo e($satisfactionScore); ?>/100
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-<?php echo e($satisfactionScore >= 70 ? 'green' : ($satisfactionScore >= 40 ? 'yellow' : 'red')); ?>-600 h-4 rounded-full" style="width: <?php echo e($satisfactionScore); ?>%"></div>
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <?php if($satisfactionScore >= 70): ?>
                    <span class="text-green-600">Client satisfait</span> - Bonne relation
                <?php elseif($satisfactionScore >= 40): ?>
                    <span class="text-yellow-600">Satisfaction moyenne</span> - Améliorations possibles
                <?php else: ?>
                    <span class="text-red-600">Client insatisfait</span> - Actions requises
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Détails des facteurs de scoring -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Détails des Facteurs de Scoring</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-medium text-gray-800 mb-2">Facteurs de Risque</h3>
                <ul class="space-y-2">
                    <li class="flex justify-between">
                        <span>Activité récente</span>
                        <span><?php echo e($client->transactions()->where('created_at', '>=', now()->subMonths(6))->exists() ? 'Actif' : 'Inactif'); ?></span>
                    </li>
                    <li class="flex justify-between">
                        <span>Nombre de réclamations</span>
                        <span><?php echo e($client->reclamations()->count()); ?></span>
                    </li>
                    <li class="flex justify-between">
                        <span>Délai de paiement</span>
                        <span><?php echo e($client->delai_paiement ?? 0); ?> jours</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Plafond de crédit</span>
                        <span><?php echo e($client->plafond_credit ? number_format($client->plafond_credit, 0, ',', ' ') . ' FCFA' : 'Non défini'); ?></span>
                    </li>
                </ul>
            </div>
            
            <div>
                <h3 class="font-medium text-gray-800 mb-2">Facteurs de Solvabilité</h3>
                <ul class="space-y-2">
                    <li class="flex justify-between">
                        <span>Statut du client</span>
                        <span><?php echo e(ucfirst($client->statut)); ?></span>
                    </li>
                    <li class="flex justify-between">
                        <span>Historique de transactions</span>
                        <span><?php echo e($client->transactions()->count()); ?> transactions</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Chiffre d'affaires total</span>
                        <span><?php echo e(number_format($client->transactions()->where('type', 'encaissement')->sum('montant'), 0, ',', ' ')); ?> FCFA</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Fidélité</span>
                        <span><?php echo e($client->transactions()->where('created_at', '>=', now()->subMonths(12))->count() >= 5 ? 'Client fidèle' : 'Client occasionnel'); ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Recommandations -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Recommandations</h2>
        
        <div class="space-y-3">
            <?php if($riskScore < 40): ?>
                <div class="p-3 bg-red-50 border border-red-200 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Risque élevé détecté</h3>
                            <div class="mt-1 text-sm text-red-700">
                                <p>Ce client présente un risque élevé. Il est recommandé de mettre en place des conditions de paiement strictes et de limiter le crédit.</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif($riskScore < 70): ?>
                <div class="p-3 bg-yellow-50 border border-yellow-200 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-yellow-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Surveillance recommandée</h3>
                            <div class="mt-1 text-sm text-yellow-700">
                                <p>Ce client nécessite une surveillance régulière. Vérifiez régulièrement son activité et son historique de paiement.</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="p-3 bg-green-50 border border-green-200 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Client fiable</h3>
                            <div class="mt-1 text-sm text-green-700">
                                <p>Ce client est considéré comme fiable. Vous pouvez envisager des conditions plus favorables.</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if($solvencyScore >= 70 && $satisfactionScore >= 70): ?>
                <div class="p-3 bg-blue-50 border border-blue-200 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-star text-blue-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Client premium</h3>
                            <div class="mt-1 text-sm text-blue-700">
                                <p>Ce client est un excellent client. Considérez des offres spéciales ou des avantages pour renforcer la relation.</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\score.blade.php ENDPATH**/ ?>