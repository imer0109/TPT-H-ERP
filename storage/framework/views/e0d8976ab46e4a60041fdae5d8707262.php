

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 p-6 space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Rapport de Présence (<?php echo e(str_pad($month, 2, '0', STR_PAD_LEFT)); ?>/<?php echo e($year); ?>)
            </h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-blue-600">Tableau de bord</a>
                <span class="mx-2">/</span>
                <span class="text-gray-700 font-medium">Rapport de Présence</span>
            </nav>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow p-6">
        <form method="GET" action="<?php echo e(route('hr.reports.attendance')); ?>"
              class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

            <div>
                <label class="text-sm font-medium text-gray-600">Mois</label>
                <select name="month"
                        class="w-full mt-1 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <?php for($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo e($m); ?>" <?php echo e($month == $m ? 'selected' : ''); ?>>
                            <?php echo e(DateTime::createFromFormat('!m', $m)->format('F')); ?>

                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Année</label>
                <select name="year"
                        class="w-full mt-1 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <?php for($y = date('Y') - 5; $y <= date('Y') + 1; $y++): ?>
                        <option value="<?php echo e($y); ?>" <?php echo e($year == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="flex gap-2">
                <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
                    🔍 Filtrer
                </button>
                <a href="<?php echo e(route('hr.reports.attendance')); ?>"
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg text-center">
                    🔄 Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Jours de Présence</p>
            <h2 class="text-3xl font-bold text-blue-600 mt-2"><?php echo e($attendanceData['present'] ?? 0); ?></h2>
            <p class="text-sm text-gray-400 mt-1">jours présents</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Taux de Présence</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2"><?php echo e($attendanceData['attendance_rate'] ?? 0); ?>%</h2>
            <p class="text-sm text-gray-400 mt-1">taux moyen</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Retards</p>
            <h2 class="text-3xl font-bold text-yellow-500 mt-2"><?php echo e($attendanceData['late'] ?? 0); ?></h2>
            <p class="text-sm text-gray-400 mt-1">jours en retard</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-indigo-500">
            <p class="text-sm text-gray-500">Heures Supplémentaires</p>
            <h2 class="text-3xl font-bold text-indigo-600 mt-2"><?php echo e(round(($attendanceData['overtime_minutes'] ?? 0) / 60, 1)); ?>h</h2>
            <p class="text-sm text-gray-400 mt-1">total mensuel</p>
        </div>

    </div>

    <!-- Attendance Analysis & Key Indicators -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Table -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Analyse de Présence</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="text-left p-2">Statut</th>
                            <th class="text-center p-2">Nombre de Jours</th>
                            <th class="text-center p-2">Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr>
                            <td class="p-2">✅ Présent</td>
                            <td class="text-center"><?php echo e($attendanceData['present'] ?? 0); ?></td>
                            <td class="text-center text-green-600 font-semibold">
                                <?php if(($attendanceData['total_days'] ?? 0) > 0): ?>
                                    <?php echo e(round((($attendanceData['present'] ?? 0) / ($attendanceData['total_days'] ?? 1)) * 100, 1)); ?>%
                                <?php else: ?>
                                    0%
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">❌ Absent</td>
                            <td class="text-center"><?php echo e($attendanceData['absent'] ?? 0); ?></td>
                            <td class="text-center text-red-600 font-semibold">
                                <?php if(($attendanceData['total_days'] ?? 0) > 0): ?>
                                    <?php echo e(round((($attendanceData['absent'] ?? 0) / ($attendanceData['total_days'] ?? 1)) * 100, 1)); ?>%
                                <?php else: ?>
                                    0%
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">⏰ En Retard</td>
                            <td class="text-center"><?php echo e($attendanceData['late'] ?? 0); ?></td>
                            <td class="text-center text-yellow-500 font-semibold">
                                <?php if(($attendanceData['total_days'] ?? 0) > 0): ?>
                                    <?php echo e(round((($attendanceData['late'] ?? 0) / ($attendanceData['total_days'] ?? 1)) * 100, 1)); ?>%
                                <?php else: ?>
                                    0%
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="bg-gray-200 font-semibold">
                            <td class="p-2">Total</td>
                            <td class="text-center"><?php echo e($attendanceData['total_days'] ?? 0); ?></td>
                            <td class="text-center">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Key Indicators -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Indicateurs Clés</h3>

            <div class="space-y-4 text-sm">
                <div>
                    <div class="flex justify-between">
                        <span>Minutes de Retard Total</span>
                        <span class="font-bold text-blue-600"><?php echo e($attendanceData['late_minutes'] ?? 0); ?> min</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded mt-1">
                        <div class="h-2 bg-blue-500 rounded w-<?php echo e(min(100, ($attendanceData['late_minutes'] ?? 0) / 10)); ?>%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between">
                        <span>Heures Supplémentaires</span>
                        <span class="font-bold text-green-600"><?php echo e(round(($attendanceData['overtime_minutes'] ?? 0) / 60, 1)); ?>h</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded mt-1">
                        <div class="h-2 bg-green-500 rounded w-<?php echo e(min(100, ($attendanceData['overtime_minutes'] ?? 0) / 600)); ?>%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between">
                        <span>Taux de Ponctualité</span>
                        <span class="font-bold text-indigo-600">
                            <?php if(($attendanceData['total_days'] ?? 0) > 0): ?>
                                <?php echo e(round((1 - (($attendanceData['late'] ?? 0) / ($attendanceData['total_days'] ?? 1))) * 100, 1)); ?>%
                            <?php else: ?>
                                0%
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded mt-1">
                        <div class="h-2 bg-indigo-500 rounded w-<?php echo e(($attendanceData['total_days'] ?? 0) > 0 ? round((1 - (($attendanceData['late'] ?? 0) / ($attendanceData['total_days'] ?? 1))) * 100, 1) : 0); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Recommandations</h3>

        <div class="bg-blue-50 p-4 rounded space-y-2 text-sm text-gray-700">
            <p>
                <strong>Taux de Présence:</strong> <?php echo e($attendanceData['attendance_rate'] ?? 0); ?>% - 
                <?php if(($attendanceData['attendance_rate'] ?? 0) >= 95): ?>
                    Excellent taux de présence
                <?php elseif(($attendanceData['attendance_rate'] ?? 0) >= 90): ?>
                    Bon taux de présence
                <?php elseif(($attendanceData['attendance_rate'] ?? 0) >= 80): ?>
                    Taux acceptable mais améliorable
                <?php else: ?>
                    Taux préoccupant
                <?php endif; ?>
            </p>
            <p>
                <strong>Retards:</strong> <?php echo e($attendanceData['late'] ?? 0); ?> jours - 
                <?php if(($attendanceData['late'] ?? 0) == 0): ?>
                    Aucun retard signalé
                <?php elseif(($attendanceData['late'] ?? 0) <= 3): ?>
                    Quelques retards
                <?php else: ?>
                    Retards significatifs à surveiller
                <?php endif; ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm text-gray-700">
            <div>
                <h6 class="font-semibold">Points Positifs:</h6>
                <ul class="list-disc list-inside space-y-1">
                    <?php if(($attendanceData['attendance_rate'] ?? 0) >= 90): ?><li>Taux de présence élevé</li><?php endif; ?>
                    <?php if(($attendanceData['late'] ?? 0) <= 2): ?><li>Bonne ponctualité générale</li><?php endif; ?>
                    <?php if(($attendanceData['overtime_minutes'] ?? 0) > 120): ?><li>Engagement démontré par les heures supplémentaires</li><?php endif; ?>
                    <li>Respect des horaires de travail</li>
                </ul>
            </div>
            <div>
                <h6 class="font-semibold">Recommandations:</h6>
                <ul class="list-disc list-inside space-y-1">
                    <?php if(($attendanceData['attendance_rate'] ?? 0) < 90): ?><li>Mettre en place un système d'alerte pour absences répétées</li><?php endif; ?>
                    <?php if(($attendanceData['late'] ?? 0) > 3): ?><li>Sensibiliser les employés à la ponctualité</li><?php endif; ?>
                    <?php if(($attendanceData['overtime_minutes'] ?? 0) > 600): ?><li>Vérifier la charge de travail des équipes</li><?php endif; ?>
                    <li>Encourager les bonnes pratiques de présence</li>
                </ul>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\hr\reports\attendance.blade.php ENDPATH**/ ?>