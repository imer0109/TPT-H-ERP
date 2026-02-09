

<?php $__env->startSection('title', 'TABLEAU DE BORD RH'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de bord Ressources Humaines</h1>
        <p class="text-gray-600 mt-2">Vue d'ensemble des activités du personnel</p>
    </div>

    <?php if (isset($component)) { $__componentOriginal47d351b5e4e609a28c99ca9ae9ccb99f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal47d351b5e4e609a28c99ca9ae9ccb99f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-filters','data' => ['action' => route('hr.dashboard'),'companies' => $companies ?? [],'agencies' => $agencies ?? [],'companyId' => $companyId ?? null,'agencyId' => $agencyId ?? null,'dateFrom' => $dateFrom ?? null,'dateTo' => $dateTo ?? null,'showCompany' => true,'showAgency' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-filters'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('hr.dashboard')),'companies' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($companies ?? []),'agencies' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($agencies ?? []),'company-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($companyId ?? null),'agency-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($agencyId ?? null),'date-from' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dateFrom ?? null),'date-to' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dateTo ?? null),'show-company' => true,'show-agency' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal47d351b5e4e609a28c99ca9ae9ccb99f)): ?>
<?php $attributes = $__attributesOriginal47d351b5e4e609a28c99ca9ae9ccb99f; ?>
<?php unset($__attributesOriginal47d351b5e4e609a28c99ca9ae9ccb99f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal47d351b5e4e609a28c99ca9ae9ccb99f)): ?>
<?php $component = $__componentOriginal47d351b5e4e609a28c99ca9ae9ccb99f; ?>
<?php unset($__componentOriginal47d351b5e4e609a28c99ca9ae9ccb99f); ?>
<?php endif; ?>

    <!-- HR Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-primary-500">
            <h3 class="text-gray-500 text-sm font-medium">Total Employés</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($totalEmployees); ?></p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e($activeEmployees); ?> actifs</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-medium">Postes</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($totalPositions); ?></p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm font-medium">Congés En Attente</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($pendingLeaves); ?></p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
            <h3 class="text-gray-500 text-sm font-medium">Bulletins En Attente</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($pendingPayslips); ?></p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Employés par Genre</h3>
            <div class="flex items-center justify-center h-64">
                <div class="relative w-48 h-48">
                    <svg class="w-full h-full" viewBox="0 0 100 100">
                        <!-- Circle chart will be rendered here by JavaScript -->
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#e5e7eb" stroke-width="8"></circle>
                        <circle id="genderChartCircle" cx="50" cy="50" r="45" fill="none" stroke="#3b82f6" stroke-width="8" 
                                stroke-dasharray="283" stroke-dashoffset="141" transform="rotate(-90 50 50)" stroke-linecap="round"></circle>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex justify-center space-x-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-primary-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">Hommes</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-pink-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">Femmes</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pointage Aujourd'hui</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-primary-50 rounded">
                    <span class="font-medium">Présents</span>
                    <span class="bg-primary-100 text-primary-800 px-2 py-1 rounded-full text-sm"><?php echo e($presentToday); ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-red-50 rounded">
                    <span class="font-medium">Absents</span>
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm"><?php echo e($absentToday); ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-yellow-50 rounded">
                    <span class="font-medium">En Retard</span>
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm"><?php echo e($lateToday); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Employees -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Nouveaux Employés</h3>
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $recentEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('hr.employees.show', $employee)); ?>" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition">
                    <div class="flex items-center">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-10 mr-3"></div>
                        <div>
                            <p class="font-medium text-gray-800"><?php echo e($employee->full_name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($employee->currentPosition->title ?? 'N/A'); ?></p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400"><?php echo e($employee->created_at->diffForHumans()); ?></span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-sm text-gray-500 py-4">Aucun nouvel employé</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Leaves -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Congés Récents</h3>
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $recentLeaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('hr.leaves.show', $leave)); ?>" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition">
                    <div>
                        <p class="font-medium text-gray-800"><?php echo e($leave->employee->full_name); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($leave->leaveType->name); ?> · <?php echo e($leave->duration); ?> jours</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full
                        <?php if($leave->status === 'approved'): ?> bg-green-100 text-green-800
                        <?php elseif($leave->status === 'rejected'): ?> bg-red-100 text-red-800
                        <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                        <?php echo e(ucfirst($leave->status)); ?>

                    </span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-sm text-gray-500 py-4">Aucune demande récente</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Today's Attendance -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pointage du Jour</h3>
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $recentAttendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition">
                    <div>
                        <p class="font-medium text-gray-800"><?php echo e($attendance->employee->full_name); ?></p>
                        <p class="text-xs text-gray-500">
                            <?php echo e($attendance->check_in?->format('H:i') ?? 'N/A'); ?> -
                            <?php echo e($attendance->check_out?->format('H:i') ?? 'N/A'); ?>

                        </p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full bg-<?php echo e($attendance->status_color); ?>-100 text-<?php echo e($attendance->status_color); ?>-800">
                        <?php echo e($attendance->status_text); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-sm text-gray-500 py-4">Aucun pointage aujourd'hui</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/hr/dashboard/index.blade.php ENDPATH**/ ?>