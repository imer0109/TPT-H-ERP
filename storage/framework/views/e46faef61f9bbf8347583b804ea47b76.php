

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Créer une Nouvelle Caisse</h1>

        <form action="<?php echo e(route('cash.registers.store')); ?>" method="POST" class="space-y-6" id="cashRegisterForm">
            <?php echo csrf_field(); ?>

            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">

                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom de la caisse</label>
                        <input type="text" name="nom" id="nom" value="<?php echo e(old('nom')); ?>" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
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
                        <input type="text" name="description" id="description" value="<?php echo e(old('description')); ?>"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
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
                        <select name="entity_type" id="entity_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <option value="">Sélectionner un type</option>
                            <option value="App\Models\Company" <?php echo e(old('entity_type') === 'App\Models\Company' ? 'selected' : ''); ?>>Société</option>
                            <option value="App\Models\Agency" <?php echo e(old('entity_type') === 'App\Models\Agency' ? 'selected' : ''); ?>>Agence</option>
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
                        <select name="entity_id" id="entity_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <option value="">Sélectionner d'abord un type</option>
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
                        <label for="type" class="block text-sm font-medium text-gray-700">Type de caisse</label>
                        <select name="type" id="type" required
                            class="mt-1 block w-full rounded-md border-gray-300 focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <option value="">Sélectionner un type</option>
                            <option value="principale" <?php echo e(old('type') === 'principale' ? 'selected' : ''); ?>>Principale</option>
                            <option value="secondaire" <?php echo e(old('type') === 'secondaire' ? 'selected' : ''); ?>>Secondaire</option>
                        </select>
                        <?php $__errorArgs = ['type'];
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
                        <label for="solde_initial" class="block text-sm font-medium text-gray-700">Solde initial</label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <input type="number" name="solde_initial" id="solde_initial"
                                value="<?php echo e(old('solde_initial', 0)); ?>" min="0" step="0.01" required
                                class="block w-full rounded-md border-gray-300 pr-12 focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 sm:text-sm">
                                FCFA
                            </div>
                        </div>
                        <?php $__errorArgs = ['solde_initial'];
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
                        <select name="active" id="active"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <option value="1" <?php echo e(old('active', 1) == 1 ? 'selected' : ''); ?>>Active</option>
                            <option value="0" <?php echo e(old('active') == 0 ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="<?php echo e(route('cash.registers.index')); ?>"
                   class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit"
                        class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    Créer
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const entityTypeSelect = document.getElementById('entity_type');
    const entityIdSelect = document.getElementById('entity_id');
    const oldEntityId = <?php echo json_encode(old('entity_id'), 15, 512) ?>;

    function loadEntities(type) {
        if (!type) return;

        fetch(`/api/entities-by-type?type=${encodeURIComponent(type)}&_=${Date.now()}`)
            .then(r => r.json())
            .then(data => {
                entityIdSelect.innerHTML = '<option value="">Sélectionner une entité</option>';
                data.forEach(e => {
                    const option = document.createElement('option');
                    option.value = e.id;
                    option.textContent = type === 'App\\Models\\Company'
                        ? e.raison_sociale ?? 'Société sans nom'
                        : e.nom ?? 'Agence sans nom';
                    entityIdSelect.appendChild(option);
                });

                if (oldEntityId) entityIdSelect.value = oldEntityId;
            });
    }

    entityTypeSelect.addEventListener('change', e => loadEntities(e.target.value));
    if (entityTypeSelect.value) loadEntities(entityTypeSelect.value);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\cash-registers\create.blade.php ENDPATH**/ ?>