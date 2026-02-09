

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-10">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
            Rapports Comptables
        </h2>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Grand Livre -->
            <div class="bg-white rounded-2xl shadow hover:shadow-lg transition duration-300 border border-gray-100 p-6 text-center">
                <i class="fas fa-book text-blue-500 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Grand Livre</h3>
                <p class="text-gray-600 mb-4">Consultez les mouvements détaillés de chaque compte comptable.</p>
                <a href="<?php echo e(route('accounting.reports.general-ledger')); ?>" 
                   class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg font-medium transition">
                   Accéder
                </a>
            </div>

            <!-- Balance Générale -->
            <div class="bg-white rounded-2xl shadow hover:shadow-lg transition duration-300 border border-gray-100 p-6 text-center">
                <i class="fas fa-balance-scale text-green-500 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Balance Générale</h3>
                <p class="text-gray-600 mb-4">Visualisez les soldes de tous les comptes à une date donnée.</p>
                <a href="<?php echo e(route('accounting.reports.trial-balance')); ?>" 
                   class="inline-block bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg font-medium transition">
                   Accéder
                </a>
            </div>

            <!-- Compte de Résultat -->
            <div class="bg-white rounded-2xl shadow hover:shadow-lg transition duration-300 border border-gray-100 p-6 text-center">
                <i class="fas fa-chart-line text-cyan-500 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Compte de Résultat</h3>
                <p class="text-gray-600 mb-4">Analysez les revenus et dépenses sur une période donnée.</p>
                <a href="<?php echo e(route('accounting.reports.income-statement')); ?>" 
                   class="inline-block bg-cyan-500 hover:bg-cyan-600 text-white px-5 py-2 rounded-lg font-medium transition">
                   Accéder
                </a>
            </div>

            <!-- Bilan -->
            <div class="bg-white rounded-2xl shadow hover:shadow-lg transition duration-300 border border-gray-100 p-6 text-center">
                <i class="fas fa-landmark text-yellow-500 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Bilan</h3>
                <p class="text-gray-600 mb-4">Consultez la situation patrimoniale de l'entreprise.</p>
                <a href="<?php echo e(route('accounting.reports.balance-sheet')); ?>" 
                   class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg font-medium transition">
                   Accéder
                </a>
            </div>

            <!-- Journaux -->
            <div class="bg-white rounded-2xl shadow hover:shadow-lg transition duration-300 border border-gray-100 p-6 text-center">
                <i class="fas fa-file-invoice text-red-500 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Journaux</h3>
                <p class="text-gray-600 mb-4">Consultez les écritures par journal comptable.</p>
                <a href="<?php echo e(route('accounting.reports.journal')); ?>" 
                   class="inline-block bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg font-medium transition">
                   Accéder
                </a>
            </div>

            <!-- Analytique -->
            <div class="bg-white rounded-2xl shadow hover:shadow-lg transition duration-300 border border-gray-100 p-6 text-center">
                <i class="fas fa-chart-pie text-gray-600 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Analytique</h3>
                <p class="text-gray-600 mb-4">Analysez les coûts par centre de coût ou projet.</p>
                <a href="<?php echo e(route('accounting.reports.analytical')); ?>" 
                   class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg font-medium transition">
                   Accéder
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\reports\index.blade.php ENDPATH**/ ?>