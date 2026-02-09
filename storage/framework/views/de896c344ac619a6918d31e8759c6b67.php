<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Tableau de Bord Gestionnaire</h1>
        <div class="text-gray-600">Bienvenue, <?php echo e(Auth::user()->prenom); ?> <?php echo e(Auth::user()->nom); ?></div>
    </div>

    <form method="get" class="grid grid-cols-1 md:grid-cols-5 gap-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Société</label>
            <select name="company_id" class="w-full border rounded px-3 py-2">
                <option value="">Toutes</option>
                <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>" <?php echo e((string)$c->id === (string)$companyId ? 'selected' : ''); ?>><?php echo e($c->raison_sociale); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Agence</label>
            <select name="agency_id" class="w-full border rounded px-3 py-2">
                <option value="">Toutes</option>
                <?php $__currentLoopData = $agencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($a->id); ?>" <?php echo e((string)$a->id === (string)$agencyId ? 'selected' : ''); ?>><?php echo e($a->nom); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Du</label>
            <input type="date" name="date_from" value="<?php echo e($dateFrom); ?>" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Au</label>
            <input type="date" name="date_to" value="<?php echo e($dateTo); ?>" class="w-full border rounded px-3 py-2">
        </div>
        <div class="flex items-end">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 w-full">Filtrer</button>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <a href="<?php echo e(route('companies.dashboard')); ?>" class="bg-white rounded-lg shadow p-6 block hover:bg-red-50 transition">
            <div class="text-sm text-gray-500">Projets actifs</div>
            <div class="mt-2 text-3xl font-semibold text-gray-900"><?php echo e($activeProjects); ?></div>
        </a>
        <a href="<?php echo e(route('teams.index')); ?>" class="bg-white rounded-lg shadow p-6 block hover:bg-red-50 transition">
            <div class="text-sm text-gray-500">Équipes</div>
            <div class="mt-2 text-3xl font-semibold text-gray-900"><?php echo e($totalTeams); ?></div>
        </a>
        <a href="<?php echo e(route('validations.requests.index')); ?>?status=pending&company_id=<?php echo e($companyId); ?>&agency_id=<?php echo e($agencyId); ?>&date_from=<?php echo e($dateFrom); ?>&date_to=<?php echo e($dateTo); ?>" class="bg-white rounded-lg shadow p-6 block hover:bg-red-50 transition">
            <div class="text-sm text-gray-500">Validations en attente</div>
            <div class="mt-2 text-3xl font-semibold text-gray-900"><?php echo e($pendingValidations); ?></div>
        </a>
        <a href="<?php echo e(route('validations.requests.index')); ?>?status=approved&company_id=<?php echo e($companyId); ?>&agency_id=<?php echo e($agencyId); ?>&date_from=<?php echo e($dateFrom); ?>&date_to=<?php echo e($dateTo); ?>" class="bg-white rounded-lg shadow p-6 block hover:bg-red-50 transition">
            <div class="text-sm text-gray-500">Validations approuvées (aujourd'hui)</div>
            <div class="mt-2 text-3xl font-semibold text-green-600"><?php echo e($approvedValidationsToday); ?></div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Performance des équipes</h3>
            <div class="h-64">
                <canvas id="managerTeamPerformance"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Charge de travail</h3>
            <div class="h-64">
                <canvas id="managerWorkload"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const teamCtx = document.getElementById('managerTeamPerformance').getContext('2d');
    const perfData = <?php echo json_encode($teamPerformanceSeries, 15, 512) ?>;
    const perfDatasets = perfData.teams.map(t => ({
        label: t.name,
        data: t.data,
        borderColor: '#3B82F6',
        backgroundColor: 'rgba(59,130,246,0.1)',
        fill: true,
        tension: 0.4
    }));
    new Chart(teamCtx, { type: 'line', data: { labels: perfData.labels, datasets: perfDatasets }, options: { responsive: true, maintainAspectRatio: false } });

    const workloadCtx = document.getElementById('managerWorkload').getContext('2d');
    const wl = <?php echo json_encode($workloadByModule, 15, 512) ?>;
    const wlLabels = wl.map(i => i.module);
    const wlData = wl.map(i => i.count);
    new Chart(workloadCtx, { type: 'bar', data: { labels: wlLabels, datasets: [{ label: 'Validations en attente', data: wlData, backgroundColor: ['#3B82F6', '#EF4444', '#F59E0B', '#10B981', '#6366F1'], borderRadius: 4 }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } } });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\dashboards\manager.blade.php ENDPATH**/ ?>