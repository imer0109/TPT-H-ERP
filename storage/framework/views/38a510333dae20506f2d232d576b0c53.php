

<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-4">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Paramètres Comptables</h1>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded mb-6">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Centres de Coût -->
        <div class="bg-white shadow rounded p-4 flex flex-col justify-between">
            <div>
                <h4 class="text-xl font-bold text-blue-600"><?php echo e($costCenters->count()); ?></h4>
                <p class="text-gray-500">Total Centres de Coût</p>
            </div>
            <div>
                <h4 class="text-xl font-bold text-green-600"><?php echo e($costCenters->where('is_active', true)->count()); ?></h4>
                <p class="text-gray-500">Actifs</p>
            </div>
            <a href="<?php echo e(route('accounting.settings.cost-centers')); ?>" class="mt-2 inline-block text-sm text-blue-600 hover:underline">
                Gérer
            </a>
        </div>

        <!-- Projets -->
        <div class="bg-white shadow rounded p-4 flex flex-col justify-between">
            <div>
                <h4 class="text-xl font-bold text-blue-600"><?php echo e($projects->count()); ?></h4>
                <p class="text-gray-500">Total Projets</p>
            </div>
            <div>
                <h4 class="text-xl font-bold text-yellow-600"><?php echo e($projects->where('status', 'en_cours')->count()); ?></h4>
                <p class="text-gray-500">En cours</p>
            </div>
            <a href="<?php echo e(route('accounting.settings.projects')); ?>" class="mt-2 inline-block text-sm text-blue-600 hover:underline">
                Gérer
            </a>
        </div>

        <!-- Journaux -->
        <div class="bg-white shadow rounded p-4 flex flex-col justify-between">
            <div>
                <h4 class="text-xl font-bold text-blue-600"><?php echo e($journals->count()); ?></h4>
                <p class="text-gray-500">Total Journaux</p>
            </div>
            <div>
                <h4 class="text-xl font-bold text-green-600"><?php echo e($journals->where('is_active', true)->count()); ?></h4>
                <p class="text-gray-500">Actifs</p>
            </div>
            <a href="<?php echo e(route('accounting.settings.journals')); ?>" class="mt-2 inline-block text-sm text-blue-600 hover:underline">
                Gérer
            </a>
        </div>

        <!-- Sociétés -->
        <div class="bg-white shadow rounded p-4 flex flex-col justify-between">
            <h4 class="text-xl font-bold text-blue-600"><?php echo e($companies->count()); ?></h4>
            <p class="text-gray-500">Total Sociétés</p>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="bg-white shadow rounded p-4">
        <h2 class="text-lg font-semibold mb-4">Actions Rapides</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?php echo e(route('accounting.settings.parameters')); ?>" class="flex flex-col items-center justify-center p-4 border rounded hover:bg-blue-50 transition">
                <i class="fas fa-sliders-h text-2xl mb-2 text-blue-600"></i>
                <span class="text-sm font-medium text-gray-700 text-center">Paramètres Généraux</span>
            </a>
            <a href="<?php echo e(route('accounting.chart-of-accounts.index')); ?>" class="flex flex-col items-center justify-center p-4 border rounded hover:bg-green-50 transition">
                <i class="fas fa-sitemap text-2xl mb-2 text-green-600"></i>
                <span class="text-sm font-medium text-gray-700 text-center">Plan Comptable</span>
            </a>
            <a href="<?php echo e(route('accounting.export.excel')); ?>" class="flex flex-col items-center justify-center p-4 border rounded hover:bg-indigo-50 transition">
                <i class="fas fa-download text-2xl mb-2 text-indigo-600"></i>
                <span class="text-sm font-medium text-gray-700 text-center">Exporter Données</span>
            </a>
            <a href="<?php echo e(route('accounting.settings.import-chart-of-accounts')); ?>" class="flex flex-col items-center justify-center p-4 border rounded hover:bg-yellow-50 transition">
                <i class="fas fa-upload text-2xl mb-2 text-yellow-600"></i>
                <span class="text-sm font-medium text-gray-700 text-center">Importer Plan</span>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\settings\index.blade.php ENDPATH**/ ?>