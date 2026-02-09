

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Détails du Poste</h1>
        <div class="mt-3 sm:mt-0 flex space-x-2">
            <a href="<?php echo e(route('hr.positions.edit', $position)); ?>" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="mdi mdi-pencil mr-1"></i> Modifier
            </a>
            <a href="<?php echo e(route('hr.positions.index')); ?>" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="mdi mdi-arrow-left mr-1"></i> Retour
            </a>
        </div>
    </div>

    <!-- Success Message -->
    <?php if(session('success')): ?>
    <div class="mb-4 rounded-md bg-green-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-green-800"><?php echo e(session('success')); ?></h3>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Position Details -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Informations du Poste</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Titre</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($position->title); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Département</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($position->department->name ?? 'N/A'); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Poste Hiérarchique Supérieur</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?php if($position->parentPosition): ?>
                            <a href="<?php echo e(route('hr.positions.show', $position->parentPosition)); ?>" class="text-blue-600 hover:text-blue-800">
                                <?php echo e($position->parentPosition->title); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-gray-500">Aucun</span>
                        <?php endif; ?>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type de Poste</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?php if($position->is_management): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Management
                            </span>
                        <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Opérationnel
                            </span>
                        <?php endif; ?>
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Hiérarchie Complète</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($position->full_hierarchy); ?></dd>
                </div>
                <?php if($position->description): ?>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap"><?php echo e($position->description); ?></dd>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Child Positions -->
    <?php if($position->childPositions->count() > 0): ?>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Postes Subordonnés</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employés</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $position->childPositions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($child->title); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($child->is_management): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Management
                            </span>
                            <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Opérationnel
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($child->employees->count()); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="<?php echo e(route('hr.positions.show', $child)); ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="mdi mdi-eye"></i>
                            </a>
                            <a href="<?php echo e(route('hr.positions.edit', $child)); ?>" class="text-indigo-600 hover:text-indigo-900">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Employees in this Position -->
    <?php if($position->employees->count() > 0): ?>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Employés dans ce Poste</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Société</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $position->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($employee->full_name); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($employee->matricule); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($employee->currentCompany->raison_sociale ?? 'N/A'); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php if($employee->status == 'active'): ?> bg-green-100 text-green-800
                                <?php elseif($employee->status == 'suspended'): ?> bg-yellow-100 text-yellow-800
                                <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                                <?php echo e($employee->status_text); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="<?php echo e(route('hr.employees.show', $employee)); ?>" class="text-blue-600 hover:text-blue-900">
                                <i class="mdi mdi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\positions\show.blade.php ENDPATH**/ ?>