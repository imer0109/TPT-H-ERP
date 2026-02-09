

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 px-4 sm:px-6 lg:px-8 py-6">

    <!-- Breadcrumb + Title -->
    <div class="mb-8">
        <nav class="text-sm text-gray-500 mb-2">
            <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-blue-600">Tableau de bord</a>
            <span class="mx-2">/</span>
            <span class="text-gray-700 font-medium">Tableau de Bord RH</span>
        </nav>
        <h1 class="text-2xl font-bold text-gray-800">
            Tableau de Bord RH – Vue d’Ensemble
        </h1>
    </div>

    <!-- ===================== -->
    <!-- SUMMARY CARDS -->
    <!-- ===================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-blue-500">
            <p class="text-xs font-semibold text-blue-500 uppercase">Effectif total</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">
                <?php echo e($headcountData['total'] ?? 0); ?>

            </p>
            <p class="text-sm text-gray-500 mt-1">
                <?php echo e($headcountData['active'] ?? 0); ?> actifs
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-green-500">
            <p class="text-xs font-semibold text-green-500 uppercase">Masse salariale</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">
                FCFA <?php echo e(number_format($payrollData['total_net'] ?? 0, 2, ',', ' ')); ?>

            </p>
            <p class="text-sm text-gray-500 mt-1">Net mensuel</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-yellow-500">
            <p class="text-xs font-semibold text-yellow-500 uppercase">Congés en cours</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">
                <?php echo e($leaveData['approved'] ?? 0); ?>

            </p>
            <p class="text-sm text-gray-500 mt-1">Demandes approuvées</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-cyan-500">
            <p class="text-xs font-semibold text-cyan-500 uppercase">Taux de présence</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">
                <?php echo e($attendanceData['attendance_rate'] ?? 0); ?>%
            </p>
            <p class="text-sm text-gray-500 mt-1">Ce mois</p>
        </div>

    </div>

    <!-- ===================== -->
    <!-- CHARTS -->
    <!-- ===================== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                Effectifs par statut
            </h3>
            <div id="headcount-status-chart"></div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                Distribution par département
            </h3>
            <div id="department-distribution-chart"></div>
        </div>

    </div>

    <!-- ===================== -->
    <!-- RECENT ACTIVITY -->
    <!-- ===================== -->
    <div class="bg-white rounded-2xl shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Activité récente (recrutements)
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Employé</th>
                        <th class="px-4 py-3 text-left">Poste</th>
                        <th class="px-4 py-3 text-center">Date</th>
                        <th class="px-4 py-3 text-center">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php $__currentLoopData = $recentHires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">
                            <?php echo e($hire->full_name); ?>

                        </td>
                        <td class="px-4 py-3">
                            <?php echo e($hire->currentPosition->title ?? 'N/A'); ?>

                        </td>
                        <td class="px-4 py-3 text-center">
                            <?php echo e($hire->date_embauche?->format('d/m/Y') ?? 'N/A'); ?>

                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-600">
                                Actif
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===================== -->
    <!-- ALERTS + TURNOVER -->
    <!-- ===================== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- Alerts -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                Alertes RH
            </h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between bg-yellow-50 p-3 rounded-xl">
                    <span>Contrats arrivant à expiration</span>
                    <span class="font-bold text-yellow-600">
                        <?php echo e($alerts['contracts_expiring'] ?? 0); ?>

                    </span>
                </div>

                <div class="flex justify-between bg-blue-50 p-3 rounded-xl">
                    <span>Périodes d’essai en fin</span>
                    <span class="font-bold text-blue-600">
                        <?php echo e($alerts['probation_ending'] ?? 0); ?>

                    </span>
                </div>

                <div class="flex justify-between bg-red-50 p-3 rounded-xl">
                    <span>Congés prolongés</span>
                    <span class="font-bold text-red-600">
                        <?php echo e($alerts['long_leaves'] ?? 0); ?>

                    </span>
                </div>

                <div class="flex justify-between bg-green-50 p-3 rounded-xl">
                    <span>Bulletins prêts</span>
                    <span class="font-bold text-green-600">
                        <?php echo e($alerts['payroll_ready'] ?? 0); ?>

                    </span>
                </div>
            </div>
        </div>

        <!-- Turnover -->
        <div class="bg-white rounded-2xl shadow p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                Taux de rotation
            </h3>

            <p class="text-4xl font-bold text-blue-600">
                <?php echo e($turnoverData['turnover_rate'] ?? 0); ?>%
            </p>

            <div class="w-full bg-gray-200 rounded-full h-2 mt-4">
                <div
                    class="h-2 rounded-full
                    <?php echo e(($turnoverData['turnover_rate'] ?? 0) < 10 ? 'bg-green-500' :
                       (($turnoverData['turnover_rate'] ?? 0) < 20 ? 'bg-yellow-500' : 'bg-red-500')); ?>"
                    style="width: <?php echo e(min(100, $turnoverData['turnover_rate'] ?? 0)); ?>%">
                </div>
            </div>

            <p class="text-sm text-gray-500 mt-3">
                <?php echo e($turnoverData['departures'] ?? 0); ?> départs /
                <?php echo e($turnoverData['hires'] ?? 0); ?> embauches
            </p>
        </div>

    </div>

    <!-- ===================== -->
    <!-- QUICK LINKS -->
    <!-- ===================== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="<?php echo e(route('hr.reports.headcount')); ?>" class="dashboard-btn">Effectifs</a>
        <a href="<?php echo e(route('hr.reports.payroll')); ?>" class="dashboard-btn">Paie</a>
        <a href="<?php echo e(route('hr.reports.leave')); ?>" class="dashboard-btn">Congés</a>
        <a href="<?php echo e(route('hr.reports.attendance')); ?>" class="dashboard-btn">Présence</a>
    </div>

</div>

<!-- ApexCharts -->
<script src="<?php echo e(asset('js/vendor/apexcharts.min.js')); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    new ApexCharts(
        document.querySelector("#headcount-status-chart"),
        {
            chart: { type: 'pie', height: 320 },
            labels: ['Actifs', 'Suspendus', 'Archivés'],
            colors: ['#22c55e', '#facc15', '#ef4444'],
            series: [
                <?php echo e($headcountData['active'] ?? 0); ?>,
                <?php echo e($headcountData['suspended'] ?? 0); ?>,
                <?php echo e($headcountData['archived'] ?? 0); ?>

            ]
        }
    ).render();

    new ApexCharts(
        document.querySelector("#department-distribution-chart"),
        {
            chart: { type: 'donut', height: 320 },
            labels: <?php echo json_encode(array_keys($departmentStats ?? []), 15, 512) ?>,
            series: <?php echo json_encode(array_values($departmentStats ?? []), 15, 512) ?>
        }
    ).render();

});
</script>

<style>
.dashboard-btn {
    @apply bg-white rounded-xl shadow p-4 text-center font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\hr\reports\dashboard.blade.php ENDPATH**/ ?>