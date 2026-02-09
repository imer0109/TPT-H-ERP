

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Nouvelle Société</h1>
        <a href="<?php echo e(route('companies.index')); ?>" class="text-gray-600 hover:text-gray-900">
            Retour à la liste
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="<?php echo e(route('companies.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-4">
                
                <div>
                    <label for="raison_sociale" class="block text-sm font-medium text-gray-700">
                        Raison Sociale <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="raison_sociale" id="raison_sociale"
                           value="<?php echo e(old('raison_sociale')); ?>"
                           class="mt-1 focus:ring-red-500  focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    <?php $__errorArgs = ['raison_sociale'];
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

                
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select name="type" id="type"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="holding" <?php echo e(old('type') == 'holding' ? 'selected' : ''); ?>>Holding</option>
                        <option value="filiale" <?php echo e(old('type') == 'filiale' ? 'selected' : ''); ?>>Filiale</option>
                    </select>
                </div>

                
                <div>
                    <label for="niu" class="block text-sm font-medium text-gray-700">NIU</label>
                    <input type="text" name="niu" id="niu" value="<?php echo e(old('niu')); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 border shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <?php $__errorArgs = ['niu'];
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

                
                <div>
                    <label for="rccm" class="block text-sm font-medium text-gray-700">RCCM</label>
                    <input type="text" name="rccm" id="rccm" value="<?php echo e(old('rccm')); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <?php $__errorArgs = ['rccm'];
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

                
                <div>
                    <label for="regime_fiscal" class="block text-sm font-medium text-gray-700">Régime Fiscal</label>
                    <input type="text" name="regime_fiscal" id="regime_fiscal" value="<?php echo e(old('regime_fiscal')); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <?php $__errorArgs = ['regime_fiscal'];
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

                
                <div>
                    <label for="secteur_activite" class="block text-sm font-medium text-gray-700">
                        Secteur d'Activité <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="secteur_activite" id="secteur_activite"
                           value="<?php echo e(old('secteur_activite')); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    <?php $__errorArgs = ['secteur_activite'];
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

                
                <div>
                    <label for="devise" class="block text-sm font-medium text-gray-700">
                        Devise <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="devise" id="devise"
                           value="<?php echo e(old('devise')); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    <?php $__errorArgs = ['devise'];
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

                
                <div>
                    <label for="pays" class="block text-sm font-medium text-gray-700">
                        Pays <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pays" id="pays"
                           value="<?php echo e(old('pays')); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    <?php $__errorArgs = ['pays'];
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

                
                <div>
                    <label for="ville" class="block text-sm font-medium text-gray-700">
                        Ville <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ville" id="ville"
                           value="<?php echo e(old('ville')); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    <?php $__errorArgs = ['ville'];
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

                
                <div class="col-span-2">
                    <label for="siege_social" class="block text-sm font-medium text-gray-700">
                        Siège Social <span class="text-red-500">*</span>
                    </label>
                    <textarea name="siege_social" id="siege_social" rows="3"
                              class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required><?php echo e(old('siege_social')); ?></textarea>
                    <?php $__errorArgs = ['siege_social'];
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

                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email"
                           value="<?php echo e(old('email')); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <?php $__errorArgs = ['email'];
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

                
                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="text" name="telephone" id="telephone"
                           value="<?php echo e(old('telephone')); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <?php $__errorArgs = ['telephone'];
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

                
                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700">WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp"
                           value="<?php echo e(old('whatsapp')); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <?php $__errorArgs = ['whatsapp'];
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

                
                <div>
                    <label for="site_web" class="block text-sm font-medium text-gray-700">Site Web</label>
                    <input type="url" name="site_web" id="site_web"
                           value="<?php echo e(old('site_web')); ?>"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <?php $__errorArgs = ['site_web'];
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

                
                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700">Société Mère</label>
                    <select name="parent_id" id="parent_id"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="">Aucune</option>
                        <?php $__currentLoopData = $holdings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($company->id); ?>" <?php echo e(old('parent_id') == $company->id ? 'selected' : ''); ?>>
                                <?php echo e($company->raison_sociale); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                    <input type="file" name="logo" id="logo"
                           accept="image/*"
                           onchange="document.getElementById('logo-preview').src = window.URL.createObjectURL(this.files[0])"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <img id="logo-preview" class="mt-3 w-24 h-24 object-contain border" />
                    <?php $__errorArgs = ['logo'];
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

                
                <div>
                    <label for="visuel" class="block text-sm font-medium text-gray-700">Visuel</label>
                    <input type="file" name="visuel" id="visuel"
                           accept="image/*"
                           onchange="document.getElementById('visuel-preview').src = window.URL.createObjectURL(this.files[0])"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <img id="visuel-preview" class="mt-3 w-24 h-24 object-contain border" />
                    <?php $__errorArgs = ['visuel'];
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

            <div class="mt-6">
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-white font-semibold py-2 px-6 rounded-lg shadow">
                    Créer la société
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/companies/create.blade.php ENDPATH**/ ?>