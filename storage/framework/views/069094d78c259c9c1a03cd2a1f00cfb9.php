

<?php $__env->startSection('title', 'Services'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Services</h1>
        <a href="<?php echo e(route('services.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Ajouter un Service
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Titre
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Description
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Date de création
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 border-b border-gray-200">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($service->title); ?></div>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-200">
                            <div class="text-sm text-gray-900"><?php echo e(Illuminate\Support\Str::limit($service->description, 50)); ?></div>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-200">
                            <div class="text-sm text-gray-900"><?php echo e($service->created_at ? $service->created_at->format('d/m/Y H:i') : 'Non défini'); ?></div>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-200">
                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('services.show', $service->id)); ?>" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <a href="<?php echo e(route('services.edit', $service->id)); ?>" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i> Éditer
                                </a>
                                <form action="<?php echo e(route('services.destroy', $service->id)); ?>" method="POST" class="inline-block">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 border-b border-gray-200 text-center text-gray-500">
                            Aucun service trouvé
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\services\index.blade.php ENDPATH**/ ?>