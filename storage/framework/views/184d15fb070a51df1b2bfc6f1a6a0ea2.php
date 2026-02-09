

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-plug text-indigo-600"></i>
            Nouveau Connecteur API
        </h3>
        <a href="<?php echo e(route('api-connectors.api-connectors.index')); ?>" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-xl p-6">
        <form action="<?php echo e(route('api-connectors.api-connectors.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Nom du Connecteur <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                           class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400"
                           placeholder="Ex: Connecteur Sage Comptabilité">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Société -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Société <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="company_id" required
                                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 pr-8 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400 appearance-none">
                            <option value="">Sélectionner une société</option>
                            <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($company->id); ?>" <?php echo e(old('company_id') == $company->id ? 'selected' : ''); ?>>
                                    <?php echo e($company->raison_sociale); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <span class="absolute right-3 top-3 text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    <?php $__errorArgs = ['company_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Type de Connecteur <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="type" required
                                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 pr-8 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400 appearance-none">
                            <option value="">Sélectionner un type</option>
                            <?php $__currentLoopData = $connectorTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(old('type') == $key ? 'selected' : ''); ?>>
                                    <?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <span class="absolute right-3 top-3 text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400"
                              placeholder="Description du connecteur..."><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Fréquence de Synchronisation -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Fréquence de Synchronisation <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="sync_frequency" required
                                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 pr-8 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400 appearance-none">
                            <option value="">Sélectionner une fréquence</option>
                            <?php $__currentLoopData = $syncFrequencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(old('sync_frequency') == $key ? 'selected' : ''); ?>>
                                    <?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <span class="absolute right-3 top-3 text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    <?php $__errorArgs = ['sync_frequency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Statut Actif -->
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="is_active" id="is_active" 
                           class="h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500" 
                           <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                    <label for="is_active" class="ml-2 block text-gray-700 font-semibold">
                        Connecteur Actif
                    </label>
                </div>
            </div>

            <!-- Configuration (will be expanded based on connector type) -->
            <div class="mt-8 border-t border-gray-200 pt-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Configuration</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- URL de l'API -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            URL de l'API
                        </label>
                        <input type="url" name="configuration[url]" value="<?php echo e(old('configuration.url')); ?>"
                               class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400"
                               placeholder="https://api.example.com">
                        <?php $__errorArgs = ['configuration.url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Clé API -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Clé API
                        </label>
                        <input type="text" name="configuration[api_key]" value="<?php echo e(old('configuration.api_key')); ?>"
                               class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400"
                               placeholder="Votre clé API">
                        <?php $__errorArgs = ['configuration.api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Nom d'utilisateur -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Nom d'utilisateur
                        </label>
                        <input type="text" name="configuration[username]" value="<?php echo e(old('configuration.username')); ?>"
                               class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400"
                               placeholder="Nom d'utilisateur">
                        <?php $__errorArgs = ['configuration.username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Mot de passe
                        </label>
                        <input type="password" name="configuration[password]" 
                               class="w-full rounded-lg border border-gray-300 py-2.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400"
                               placeholder="Mot de passe">
                        <?php $__errorArgs = ['configuration.password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex justify-end gap-3">
                <a href="<?php echo e(route('api-connectors.api-connectors.index')); ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-save mr-2"></i> Créer le Connecteur
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\api-connectors\create.blade.php ENDPATH**/ ?>