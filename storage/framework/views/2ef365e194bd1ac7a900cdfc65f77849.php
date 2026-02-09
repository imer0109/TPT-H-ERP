

<?php $__env->startSection('title', $service->title); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800"><?php echo e($service->title); ?></h1>
                    <p class="text-gray-600 mt-2"><?php echo e($service->description); ?></p>
                </div>
                <div class="flex space-x-2">
                    <a href="<?php echo e(route('services.edit', $service->id)); ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit"></i> Éditer
                    </a>
                    <a href="<?php echo e(route('services.index')); ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Retour
                    </a>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Contenu du service</h2>
                <div class="prose max-w-none">
                    <?php echo nl2br(e($service->content)); ?>

                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-semibold">Créé le :</span>
                        <?php echo e($service->created_at ? $service->created_at->format('d/m/Y H:i') : 'Non défini'); ?>

                    </div>
                    <div>
                        <span class="font-semibold">Mis à jour le :</span>
                        <?php echo e($service->updated_at ? $service->updated_at->format('d/m/Y H:i') : 'Non défini'); ?>

                    </div>
                </div>
            </div>

            <div class="mt-8">
                <form action="<?php echo e(route('services.destroy', $service->id)); ?>" method="POST" class="inline-block">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">
                        <i class="fas fa-trash"></i> Supprimer le service
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\services\show.blade.php ENDPATH**/ ?>