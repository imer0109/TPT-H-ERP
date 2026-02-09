

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-semibold flex items-center space-x-2">
                <i class="fas fa-sliders-h"></i>
                <span>Paramètres Généraux</span>
            </h3>
            <a href="<?php echo e(route('accounting.settings.index')); ?>" 
               class="btn-secondary inline-flex items-center px-4 py-2 rounded-md border border-gray-300 bg-gray-100 text-gray-700 hover:bg-gray-200">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>

        <!-- Alerts -->
        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="<?php echo e(route('accounting.settings.parameters.update')); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Exercice Comptable -->
                <div class="bg-white shadow rounded-lg p-4">
                    <h5 class="font-semibold text-gray-700 flex items-center space-x-2 mb-4">
                        <i class="fas fa-calendar-alt"></i> <span>Exercice Comptable</span>
                    </h5>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-600">Date de début de l'exercice *</label>
                            <input type="date" name="fiscal_year_start" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md p-2" 
                                   value="<?php echo e(old('fiscal_year_start', date('Y') . '-01-01')); ?>" required>
                        </div>
                        <div>
                            <label class="block text-gray-600">Date de fin de l'exercice *</label>
                            <input type="date" name="fiscal_year_end" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md p-2" 
                                   value="<?php echo e(old('fiscal_year_end', date('Y') . '-12-31')); ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Paramètres Généraux -->
                <div class="bg-white shadow rounded-lg p-4">
                    <h5 class="font-semibold text-gray-700 flex items-center space-x-2 mb-4">
                        <i class="fas fa-cogs"></i> <span>Paramètres Généraux</span>
                    </h5>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-600">Devise par défaut *</label>
                            <select name="default_currency" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                                <option value="EUR" <?php echo e(old('default_currency', 'EUR') == 'EUR' ? 'selected' : ''); ?>>Euro (€)</option>
                                <option value="USD" <?php echo e(old('default_currency', 'EUR') == 'USD' ? 'selected' : ''); ?>>Dollar ($)</option>
                                <option value="XOF" <?php echo e(old('default_currency', 'EUR') == 'XOF' ? 'selected' : ''); ?>>Franc CFA (CFA)</option>
                                <option value="XAF" <?php echo e(old('default_currency', 'EUR') == 'XAF' ? 'selected' : ''); ?>>Franc CFA (FCFA)</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="auto_numbering" name="auto_numbering" class="h-5 w-5 text-blue-600" <?php echo e(old('auto_numbering', true) ? 'checked' : ''); ?>>
                            <label for="auto_numbering" class="text-gray-700">Numérotation automatique des écritures</label>
                        </div>
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="validation_required" name="validation_required" class="h-5 w-5 text-blue-600" <?php echo e(old('validation_required', true) ? 'checked' : ''); ?>>
                            <label for="validation_required" class="text-gray-700">Validation requise pour les écritures</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paramètres par Société -->
            <div class="bg-white shadow rounded-lg p-4">
                <h5 class="font-semibold text-gray-700 flex items-center space-x-2 mb-4">
                    <i class="fas fa-building"></i> <span>Paramètres par Société</span>
                </h5>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Société</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Code Comptable</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Plan Comptable</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="px-4 py-2"><?php echo e($company->raison_sociale); ?></td>
                                    <td class="px-4 py-2"><?php echo e($company->code_comptable ?? 'Non défini'); ?></td>
                                    <td class="px-4 py-2"><?php echo e($company->chartOfAccount ? 'Configuré' : 'Non configuré'); ?></td>
                                    <td class="px-4 py-2">
                                        <a href="<?php echo e(route('companies.edit', $company)); ?>" 
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                           <i class="fas fa-edit mr-1"></i> Modifier
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">Aucune société trouvée</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 flex items-center space-x-2">
                    <i class="fas fa-save"></i> <span>Enregistrer les paramètres</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\settings\parameters.blade.php ENDPATH**/ ?>