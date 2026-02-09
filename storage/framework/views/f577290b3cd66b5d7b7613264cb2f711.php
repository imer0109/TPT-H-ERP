

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Gestion des Caisses</h1>
        <a href="<?php echo e(route('cash.registers.create')); ?>" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
            Nouvelle Caisse
        </a>
    </div>

    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo e(session('success')); ?></span>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo e(session('error')); ?></span>
    </div>
    <?php endif; ?>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Nom</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Entité</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Solde actuel</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $cashRegisters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cashRegister): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($cashRegister->nom); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($cashRegister->type === 'principale' ? 'bg-primary-100 text-primary-800' : 'bg-gray-100 text-gray-800'); ?>">
                            <?php echo e($cashRegister->type === 'principale' ? 'Principale' : 'Secondaire'); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php if($cashRegister->entity): ?>
                            <?php if($cashRegister->entity instanceof \App\Models\Company): ?>
                                Société : <?php echo e($cashRegister->entity->raison_sociale); ?>

                            <?php elseif($cashRegister->entity instanceof \App\Models\Agency): ?>
                                Agence : <?php echo e($cashRegister->entity->nom); ?>

                            <?php else: ?>
                                <?php echo e(class_basename($cashRegister->entity_type)); ?> : <?php echo e($cashRegister->entity->nom ?? $cashRegister->entity->raison_sociale ?? 'N/A'); ?>

                            <?php endif; ?>
                        <?php else: ?>
                            Non assignée
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e(number_format($cashRegister->solde_actuel, 2, ',', ' ')); ?> FCFA</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($cashRegister->est_ouverte ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                            <?php echo e($cashRegister->est_ouverte ? 'Ouverte' : 'Fermée'); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="<?php echo e(route('cash.registers.show', ['cashRegister' => $cashRegister->id])); ?>" class="text-primary-600 hover:text-primary-900 mr-3">Détails</a>
                        <a href="<?php echo e(route('cash.registers.edit', ['cashRegister' => $cashRegister->id])); ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                        <form action="<?php echo e(route('cash.registers.destroy', ['cashRegister' => $cashRegister->id])); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette caisse ?')">
                                Supprimer
                            </button> 
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Aucune caisse trouvée</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <?php echo e($cashRegisters->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/cash/registers/index.blade.php ENDPATH**/ ?>