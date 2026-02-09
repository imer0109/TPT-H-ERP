

<?php $__env->startSection('title', 'Détails du Workflow de Validation'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Détails du Workflow: <?php echo e($workflow->name); ?></h2>
                    <div>
                        <a href="<?php echo e(route('validations.workflows.edit', $workflow)); ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                            Modifier
                        </a>
                        <form action="<?php echo e(route('validations.workflows.destroy', $workflow)); ?>" 
                              method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce workflow ?')">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>

                <?php if(session('success')): ?>
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Informations Générales</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nom</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($workflow->name); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($workflow->description ?? 'Aucune description'); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Module</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($workflow->module); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Type d'Entité</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e(class_basename($workflow->entity_type)); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Entreprise</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($workflow->company->name ?? 'Toutes les entreprises'); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <?php if($workflow->is_active): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Actif
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactif
                                            </span>
                                        <?php endif; ?>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Statistiques</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nombre d'Étapes</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e(count($workflow->steps ?? [])); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Demandes Créées</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($workflow->validationRequests()->count()); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Demandes Approuvées</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($workflow->validationRequests()->approved()->count()); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Demandes Rejetées</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($workflow->validationRequests()->rejected()->count()); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Demandes En Attente</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($workflow->validationRequests()->pending()->count()); ?></dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Étapes de Validation</h3>
                    <?php if(!empty($workflow->steps)): ?>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <ol class="relative border-l border-gray-200">
                                <?php $__currentLoopData = $workflow->steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="mb-10 ml-6">
                                        <span class="absolute flex items-center justify-center w-8 h-8 bg-red-100 rounded-full -left-4 ring-4 ring-white">
                                            <span class="text-red-600 font-bold"><?php echo e($index + 1); ?></span>
                                        </span>
                                        <h4 class="font-medium text-gray-900"><?php echo e($step['name']); ?></h4>
                                        <p class="text-sm text-gray-500"><?php echo e($step['description'] ?? 'Aucune description'); ?></p>
                                        <div class="mt-2 text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Rôle: <?php echo e($step['role']); ?>

                                            </span>
                                            <?php if(!empty($step['timeout_hours'])): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                                    Délai: <?php echo e($step['timeout_hours']); ?>h
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ol>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500">Aucune étape définie pour ce workflow.</p>
                    <?php endif; ?>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Demandes Récentes</h3>
                    <?php if($workflow->validationRequests->count() > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Entité
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Demandeur
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Statut
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__currentLoopData = $workflow->validationRequests->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <?php echo e(class_basename($request->entity_type)); ?> #<?php echo e($request->entity_id); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo e($request->requester->name ?? 'Utilisateur inconnu'); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo e($request->created_at->format('d/m/Y H:i')); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php switch($request->status):
                                                    case ('pending'): ?>
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            En attente
                                                        </span>
                                                        <?php break; ?>
                                                    <?php case ('approved'): ?>
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Approuvé
                                                        </span>
                                                        <?php break; ?>
                                                    <?php case ('rejected'): ?>
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Rejeté
                                                        </span>
                                                        <?php break; ?>
                                                <?php endswitch; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="<?php echo e(route('validations.requests.show', $request)); ?>" 
                                                   class="text-blue-600 hover:text-blue-900">
                                                    Voir
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500">Aucune demande de validation pour ce workflow.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\validations\workflows\show.blade.php ENDPATH**/ ?>