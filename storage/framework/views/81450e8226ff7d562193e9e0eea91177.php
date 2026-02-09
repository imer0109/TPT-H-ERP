

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-700">Rapport d'Effectifs (Headcount)</h2>
            <ol class="flex space-x-2 text-gray-500 text-sm hidden print:flex">
                <li><a href="<?php echo e(route('dashboard')); ?>" class="text-blue-600">Tableau de bord</a></li>
                <li>/</li>
                <li>Rapport d'Effectifs</li>
            </ol>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-blue-500 p-4 rounded shadow-sm flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Total Employés</p>
                <h3 class="text-blue-600 text-xl font-bold"><?php echo e($employees->count()); ?></h3>
            </div>
            <i class="mdi mdi-account-group text-blue-500 text-2xl"></i>
        </div>
        <div class="bg-white border border-green-500 p-4 rounded shadow-sm flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Employés Actifs</p>
                <h3 class="text-green-600 text-xl font-bold"><?php echo e($employees->where('status', 'active')->count()); ?></h3>
            </div>
            <i class="mdi mdi-account-check text-green-500 text-2xl"></i>
        </div>
        <div class="bg-white border border-yellow-500 p-4 rounded shadow-sm flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Suspendus</p>
                <h3 class="text-yellow-600 text-xl font-bold"><?php echo e($employees->where('status', 'suspended')->count()); ?></h3>
            </div>
            <i class="mdi mdi-account-clock text-yellow-500 text-2xl"></i>
        </div>
        <div class="bg-white border border-red-500 p-4 rounded shadow-sm flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Archivés</p>
                <h3 class="text-red-600 text-xl font-bold"><?php echo e($employees->where('status', 'archived')->count()); ?></h3>
            </div>
            <i class="mdi mdi-account-off text-red-500 text-2xl"></i>
        </div>
    </div>

    <!-- Breakdown Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

        <!-- By Company -->
        <div class="bg-white rounded shadow-sm p-4">
            <h4 class="text-lg font-semibold mb-3">Répartition par Société</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-3 text-left">Société</th>
                            <th class="py-2 px-3 text-center">Effectif</th>
                            <th class="py-2 px-3 text-center">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $companyCounts = $employees->groupBy('currentCompany.raison_sociale')->map(fn($group) => $group->count());
                            $totalEmployees = $employees->count();
                        ?>
                        <?php $__currentLoopData = $companyCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $companyName => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b">
                            <td class="py-2 px-3"><?php echo e($companyName ?: 'Non assigné'); ?></td>
                            <td class="py-2 px-3 text-center"><?php echo e($count); ?></td>
                            <td class="py-2 px-3 text-center"><?php echo e($totalEmployees > 0 ? round(($count / $totalEmployees) * 100, 1) : 0); ?>%</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- By Position -->
        <div class="bg-white rounded shadow-sm p-4">
            <h4 class="text-lg font-semibold mb-3">Répartition par Poste</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-3 text-left">Poste</th>
                            <th class="py-2 px-3 text-center">Effectif</th>
                            <th class="py-2 px-3 text-center">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $positionCounts = $employees->groupBy('currentPosition.title')->map(fn($group) => $group->count());
                        ?>
                        <?php $__currentLoopData = $positionCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $positionName => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b">
                            <td class="py-2 px-3"><?php echo e($positionName ?: 'Non assigné'); ?></td>
                            <td class="py-2 px-3 text-center"><?php echo e($count); ?></td>
                            <td class="py-2 px-3 text-center"><?php echo e($totalEmployees > 0 ? round(($count / $totalEmployees) * 100, 1) : 0); ?>%</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- By Agency -->
        <div class="bg-white rounded shadow-sm p-4">
            <h4 class="text-lg font-semibold mb-3">Répartition par Agence</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-3 text-left">Agence</th>
                            <th class="py-2 px-3 text-center">Effectif</th>
                            <th class="py-2 px-3 text-center">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $agencyCounts = $employees->groupBy('currentAgency.nom')->map(fn($group) => $group->count());
                        ?>
                        <?php $__currentLoopData = $agencyCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agencyName => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b">
                            <td class="py-2 px-3"><?php echo e($agencyName ?: 'Non assigné'); ?></td>
                            <td class="py-2 px-3 text-center"><?php echo e($count); ?></td>
                            <td class="py-2 px-3 text-center"><?php echo e($totalEmployees > 0 ? round(($count / $totalEmployees) * 100, 1) : 0); ?>%</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- By Status -->
        <div class="bg-white rounded shadow-sm p-4">
            <h4 class="text-lg font-semibold mb-3">Répartition par Statut</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-3 text-left">Statut</th>
                            <th class="py-2 px-3 text-center">Effectif</th>
                            <th class="py-2 px-3 text-center">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $statusCounts = $employees->groupBy('status')->map(fn($group) => $group->count());
                        ?>
                        <?php $__currentLoopData = $statusCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b">
                            <td class="py-2 px-3">
                                <span class="px-2 py-1 rounded-full text-white <?php echo e($status === 'active' ? 'bg-green-500' : ($status === 'suspended' ? 'bg-yellow-500' : 'bg-red-500')); ?>">
                                    <?php echo e(ucfirst($status)); ?>

                                </span>
                            </td>
                            <td class="py-2 px-3 text-center"><?php echo e($count); ?></td>
                            <td class="py-2 px-3 text-center"><?php echo e($totalEmployees > 0 ? round(($count / $totalEmployees) * 100, 1) : 0); ?>%</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detailed Employee List -->
    <div class="bg-white rounded shadow-sm p-4">
        <h4 class="text-lg font-semibold mb-3">Liste Détaillée des Employés</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-3">Matricule</th>
                        <th class="py-2 px-3">Nom Complet</th>
                        <th class="py-2 px-3">Poste</th>
                        <th class="py-2 px-3">Société</th>
                        <th class="py-2 px-3">Agence</th>
                        <th class="py-2 px-3">Statut</th>
                        <th class="py-2 px-3">Date Embauche</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-b">
                        <td class="py-2 px-3"><?php echo e($employee->matricule); ?></td>
                        <td class="py-2 px-3 flex items-center gap-2">
                            
                            <span><?php echo e($employee->prenom); ?> <?php echo e($employee->nom); ?></span>
                        </td>
                        <td class="py-2 px-3"><?php echo e($employee->currentPosition->title ?? 'N/A'); ?></td>
                        <td class="py-2 px-3"><?php echo e($employee->currentCompany->raison_sociale ?? 'N/A'); ?></td>
                        <td class="py-2 px-3"><?php echo e($employee->currentAgency->nom ?? 'N/A'); ?></td>
                        <td class="py-2 px-3">
                            <span class="px-2 py-1 rounded-full text-white <?php echo e($employee->status === 'active' ? 'bg-green-500' : ($employee->status === 'suspended' ? 'bg-yellow-500' : 'bg-red-500')); ?>">
                                <?php echo e(ucfirst($employee->status)); ?>

                            </span>
                        </td>
                        <td class="py-2 px-3"><?php echo e($employee->date_embauche ? \Carbon\Carbon::parse($employee->date_embauche)->format('d/m/Y') : 'N/A'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\hr\reports\headcount.blade.php ENDPATH**/ ?>