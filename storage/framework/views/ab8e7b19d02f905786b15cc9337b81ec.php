

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Modifier la Caisse</h1>

        <form action="<?php echo e(route('cash-registers.update', $cashRegister)); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom de la caisse</label>
                        <input type="text" name="nom" id="nom" value="<?php echo e(old('nom', $cashRegister->nom)); ?>" required
                            class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <input type="text" name="description" id="description" value="<?php echo e(old('description', $cashRegister->description)); ?>"
                            class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="entity_type" class="block text-sm font-medium text-gray-700">Type d'entité</label>
                        <select name="entity_type" id="entity_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                            <option value="">Sélectionner un type</option>
                            <option value="App\Models\Company" <?php echo e(old('entity_type', $cashRegister->entity_type) === 'App\Models\Company' ? 'selected' : ''); ?>>Société</option>
                            <option value="App\Models\Agency" <?php echo e(old('entity_type', $cashRegister->entity_type) === 'App\Models\Agency' ? 'selected' : ''); ?>>Agence</option>
                        </select>
                        <?php $__errorArgs = ['entity_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="entity_id" class="block text-sm font-medium text-gray-700">Entité</label>
                        <select name="entity_id" id="entity_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                            <option value="">Chargement...</option>
                        </select>
                        <?php $__errorArgs = ['entity_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="active" class="block text-sm font-medium text-gray-700">Statut</label>
                        <select name="active" id="active" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                            <option value="1" <?php echo e(old('active', $cashRegister->active) == '1' ? 'selected' : ''); ?>>Active</option>
                            <option value="0" <?php echo e(old('active', $cashRegister->active) == '0' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                        <?php $__errorArgs = ['active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="<?php echo e(route('cash-registers.index')); ?>" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Annuler
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const entityTypeSelect = document.getElementById('entity_type');
        const entityIdSelect = document.getElementById('entity_id');
        const currentEntityId = '<?php echo e($cashRegister->entity_id); ?>';
        const currentEntityType = '<?php echo e($cashRegister->entity_type); ?>';

        function loadEntities(entityType, selectedId = null) {
            if (entityType) {
                fetch(`/api/entities-by-type?type=${entityType}`)
                    .then(response => response.json())
                    .then(data => {
                        entityIdSelect.innerHTML = '<option value="">Sélectionner une entité</option>';
                        data.forEach(entity => {
                            const option = document.createElement('option');
                            option.value = entity.id;
                            option.textContent = entity.nom;
                            if (selectedId && entity.id == selectedId) {
                                option.selected = true;
                            }
                            entityIdSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des entités:', error);
                        entityIdSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    });
            } else {
                entityIdSelect.innerHTML = '<option value="">Sélectionner d'abord un type d'entité</option>';
            }
        }

        // Chargement initial des entités
        if (currentEntityType) {
            loadEntities(currentEntityType, currentEntityId);
        }

        // Événement de changement du type d'entité
        entityTypeSelect.addEventListener('change', function() {
            loadEntities(this.value);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\cash-registers\edit.blade.php ENDPATH**/ ?>