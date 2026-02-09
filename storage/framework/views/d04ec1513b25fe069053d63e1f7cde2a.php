

<?php $__env->startSection('title', 'Modifier le Workflow de Validation'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier le Workflow de Validation</h2>

                <form action="<?php echo e(route('validations.workflows.update', $workflow)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="grid grid-cols-1 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom du Workflow</label>
                            <input type="text" name="name" id="name" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                   value="<?php echo e(old('name', $workflow->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"><?php echo e(old('description', $workflow->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="module" class="block text-sm font-medium text-gray-700">Module</label>
                                <select name="module" id="module" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                        required>
                                    <option value="">Sélectionner un module</option>
                                    <option value="accounting" <?php echo e(old('module', $workflow->module) == 'accounting' ? 'selected' : ''); ?>>Comptabilité</option>
                                    <option value="purchases" <?php echo e(old('module', $workflow->module) == 'purchases' ? 'selected' : ''); ?>>Achats</option>
                                    <option value="inventory" <?php echo e(old('module', $workflow->module) == 'inventory' ? 'selected' : ''); ?>>Stock</option>
                                    <option value="hr" <?php echo e(old('module', $workflow->module) == 'hr' ? 'selected' : ''); ?>>Ressources Humaines</option>
                                    <option value="sales" <?php echo e(old('module', $workflow->module) == 'sales' ? 'selected' : ''); ?>>Ventes</option>
                                    <option value="companies" <?php echo e(old('module', $workflow->module) == 'companies' ? 'selected' : ''); ?>>Sociétés</option>
                                    <option value="agencies" <?php echo e(old('module', $workflow->module) == 'agencies' ? 'selected' : ''); ?>>Agences</option>
                                </select>
                                <?php $__errorArgs = ['module'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="entity_type" class="block text-sm font-medium text-gray-700">Type d'Entité</label>
                                <input type="text" name="entity_type" id="entity_type" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                       value="<?php echo e(old('entity_type', $workflow->entity_type)); ?>" required>
                                <?php $__errorArgs = ['entity_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div>
                            <label for="company_id" class="block text-sm font-medium text-gray-700">Entreprise</label>
                            <select name="company_id" id="company_id" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="">Toutes les entreprises</option>
                                <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($company->id); ?>" <?php echo e(old('company_id', $workflow->company_id) == $company->id ? 'selected' : ''); ?>>
                                        <?php echo e($company->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['company_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" 
                                   class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded"
                                   <?php echo e(old('is_active', $workflow->is_active) ? 'checked' : ''); ?>>
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Workflow actif
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Étapes de Validation</h3>
                        
                        <div id="steps-container">
                            <?php $__currentLoopData = $workflow->steps ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="step-item border border-gray-200 rounded-md p-4 mb-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="text-md font-medium text-gray-900">Étape <?php echo e($index + 1); ?></h4>
                                        <button type="button" class="remove-step text-red-600 hover:text-red-900">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nom de l'étape</label>
                                            <input type="text" name="steps[<?php echo e($index); ?>][name]" 
                                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                                   value="<?php echo e($step['name'] ?? ''); ?>" required>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Rôle</label>
                                            <input type="text" name="steps[<?php echo e($index); ?>][role]" 
                                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                                   value="<?php echo e($step['role'] ?? ''); ?>" required>
                                        </div>
                                        
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Description</label>
                                            <textarea name="steps[<?php echo e($index); ?>][description]" rows="2"
                                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"><?php echo e($step['description'] ?? ''); ?></textarea>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Délai (heures)</label>
                                            <input type="number" name="steps[<?php echo e($index); ?>][timeout_hours]" min="1"
                                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                                   value="<?php echo e($step['timeout_hours'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <button type="button" id="add-step" 
                                class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Ajouter une étape
                        </button>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="<?php echo e(route('validations.workflows.index')); ?>" 
                           class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Mettre à jour le Workflow
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let stepIndex = <?php echo e(count($workflow->steps ?? [])); ?>;
    
    document.getElementById('add-step').addEventListener('click', function() {
        const stepContainer = document.createElement('div');
        stepContainer.className = 'step-item border border-gray-200 rounded-md p-4 mb-4';
        stepContainer.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <h4 class="text-md font-medium text-gray-900">Étape ${stepIndex + 1}</h4>
                <button type="button" class="remove-step text-red-600 hover:text-red-900">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom de l'étape</label>
                    <input type="text" name="steps[${stepIndex}][name]" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rôle</label>
                    <input type="text" name="steps[${stepIndex}][role]" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                           placeholder="directeur_general" required>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="steps[${stepIndex}][description]" rows="2"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Délai (heures)</label>
                    <input type="number" name="steps[${stepIndex}][timeout_hours]" min="1"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                </div>
            </div>
        `;
        
        document.getElementById('steps-container').appendChild(stepContainer);
        stepIndex++;
        
        // Add event listener to remove button
        stepContainer.querySelector('.remove-step').addEventListener('click', function() {
            stepContainer.remove();
        });
    });
    
    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-step').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.step-item').remove();
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\validations\workflows\edit.blade.php ENDPATH**/ ?>