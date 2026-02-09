

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Segmentation des Clients</h1>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('clients.index')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-list mr-2"></i> Liste des Clients
            </a>
            <a href="<?php echo e(route('clients.dashboard')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-chart-line mr-2"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="<?php echo e(route('clients.segments')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="segment" class="block text-sm font-medium text-gray-700 mb-1">Segment</label>
                <select name="segment" id="segment" 
                    class="w-full rounded-md border-2 border-gray-400 bg-white text-gray-900 px-3 py-2 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-200">
                    <option value="">Tous les segments</option>
                    <?php $__currentLoopData = $segmentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('segment') == $key ? 'selected' : ''); ?>>
                            <?php echo e($label); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end space-x-2">
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
                <a href="<?php echo e(route('clients.segments')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-times mr-2"></i> Réinitialiser
                </a>
                <a href="<?php echo e(route('clients.segments.export', request()->all())); ?>" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-file-export mr-2"></i> Exporter
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des clients segmentés -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom/Raison sociale</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Société</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agence</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo e($client->code_client); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($client->nom_raison_sociale); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php echo e($client->type_client == 'particulier' ? 'bg-blue-100 text-blue-800' : ''); ?>

                            <?php echo e($client->type_client == 'entreprise' ? 'bg-green-100 text-green-800' : ''); ?>

                            <?php echo e($client->type_client == 'administration' ? 'bg-purple-100 text-purple-800' : ''); ?>

                            <?php echo e($client->type_client == 'distributeur' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                        ">
                            <?php echo e(ucfirst($client->type_client)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <div><?php echo e($client->telephone); ?></div>
                        <div><?php echo e($client->email); ?></div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($client->company->raison_sociale ?? 'N/A'); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($client->agency->nom ?? 'N/A'); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php echo e($client->statut == 'actif' ? 'bg-green-100 text-green-800' : ''); ?>

                            <?php echo e($client->statut == 'inactif' ? 'bg-gray-100 text-gray-800' : ''); ?>

                            <?php echo e($client->statut == 'suspendu' ? 'bg-red-100 text-red-800' : ''); ?>

                        ">
                            <?php echo e(ucfirst($client->statut)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('clients.show', $client)); ?>" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-6 py-4 text-sm text-gray-500 text-center">Aucun client trouvé</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="px-6 py-4">
            <?php echo e($clients->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\segments.blade.php ENDPATH**/ ?>