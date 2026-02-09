

<?php $__env->startSection('title', 'Détails de la Demande de Validation'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Détails de la Demande #<?php echo e($request->id); ?></h2>
                    <a href="<?php echo e(route('validations.requests.index')); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Retour
                    </a>
                </div>

                <?php if(session('success')): ?>
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="lg:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de la Demande</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Workflow</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($request->workflow->name ?? 'Workflow inconnu'); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Entité</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e(class_basename($request->entity_type)); ?> #<?php echo e($request->entity_id); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Demandeur</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($request->requester->name ?? 'Utilisateur inconnu'); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Entreprise</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($request->company->name ?? 'Non spécifiée'); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date de Création</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($request->created_at->format('d/m/Y H:i')); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
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
                                    </dd>
                                </div>
                                <?php if($request->status === 'rejected'): ?>
                                    <div class="md:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Raison du Rejet</dt>
                                        <dd class="mt-1 text-sm text-gray-900"><?php echo e($request->rejection_reason); ?></dd>
                                    </div>
                                <?php endif; ?>
                                <?php if($request->reason): ?>
                                    <div class="md:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Raison de la Demande</dt>
                                        <dd class="mt-1 text-sm text-gray-900"><?php echo e($request->reason); ?></dd>
                                    </div>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                        <?php if($request->status === 'pending' && Auth::user()->canValidateRequest($request)): ?>
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <form action="<?php echo e(route('validations.requests.approve', $request)); ?>" method="POST" class="mb-4">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label for="approval_notes" class="block text-sm font-medium text-gray-700">Notes (optionnel)</label>
                                        <textarea name="notes" id="approval_notes" rows="2"
                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"></textarea>
                                    </div>
                                    <button type="submit" 
                                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Approuver
                                    </button>
                                </form>
                                
                                <form action="<?php echo e(route('validations.requests.reject', $request)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Raison du Rejet <span class="text-red-500">*</span></label>
                                        <textarea name="reason" id="rejection_reason" rows="2" required
                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"></textarea>
                                    </div>
                                    <button type="submit" 
                                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Rejeter
                                    </button>
                                </form>
                            </div>
                        <?php elseif($request->status === 'pending'): ?>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-sm text-yellow-800">
                                    Cette demande est en attente de validation par un autre utilisateur.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-800">
                                    Cette demande a déjà été traitée.
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Étapes de Validation</h3>
                    <?php if($request->validationSteps->count() > 0): ?>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <ol class="relative border-l border-gray-200">
                                <?php $__currentLoopData = $request->validationSteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="mb-10 ml-6">
                                        <span class="absolute flex items-center justify-center w-8 h-8 bg-red-100 rounded-full -left-4 ring-4 ring-white">
                                            <span class="text-red-600 font-bold"><?php echo e($step->step_number + 1); ?></span>
                                        </span>
                                        <div class="flex items-center justify-between">
                                            <h4 class="font-medium text-gray-900"><?php echo e($step->validator->name ?? 'Validateur inconnu'); ?></h4>
                                            <span class="text-sm font-medium text-gray-500"><?php echo e($step->created_at->format('d/m/Y H:i')); ?></span>
                                        </div>
                                        <p class="text-sm text-gray-500"><?php echo e($step->getActionDisplayName()); ?></p>
                                        <?php if($step->notes): ?>
                                            <p class="mt-1 text-sm text-gray-700 bg-white p-2 rounded"><?php echo e($step->notes); ?></p>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ol>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500">Aucune étape de validation effectuée pour cette demande.</p>
                    <?php endif; ?>
                </div>

                <?php if($request->data_snapshot): ?>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Données de l'Entité</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <pre class="text-sm text-gray-700 overflow-x-auto"><?php echo e(json_encode($request->data_snapshot, JSON_PRETTY_PRINT)); ?></pre>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\validations\requests\show.blade.php ENDPATH**/ ?>