

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Plan Comptable</h1>
                <p class="text-gray-600 mt-1">Gestion du plan comptable par société</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('accounting.chart-of-accounts.tree')); ?>" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-sitemap mr-2"></i>Vue Arbre
                </a>
                <a href="<?php echo e(route('accounting.chart-of-accounts.import.form')); ?>" 
                   class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-upload mr-2"></i>Importer
                </a>
                <a href="<?php echo e(route('accounting.chart-of-accounts.create')); ?>" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Nouveau Compte
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                       placeholder="Code ou libellé..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Société</label>
                <select name="company_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Toutes les sociétés</option>
                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($company->id); ?>" <?php echo e(request('company_id') == $company->id ? 'selected' : ''); ?>><?php echo e($company->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="account_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Tous les types</option>
                    <?php $__currentLoopData = $accountTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('account_type') == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nature</label>
                <select name="aux_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Toutes les natures</option>
                    <?php $__currentLoopData = $auxTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('aux_type') == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="is_active" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Tous les statuts</option>
                    <option value="1" <?php echo e(request('is_active') === '1' ? 'selected' : ''); ?>>Actif</option>
                    <option value="0" <?php echo e(request('is_active') === '0' ? 'selected' : ''); ?>>Inactif</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des comptes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Libellé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Nature</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Société</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-primary-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($account->code); ?></div>
                                <?php if($account->syscohada_code): ?>
                                    <div class="text-xs text-gray-500">SYSCOHADA: <?php echo e($account->syscohada_code); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?php echo e($account->label); ?></div>
                                <?php if($account->parent): ?>
                                    <div class="text-xs text-gray-500">Parent: <?php echo e($account->parent->code); ?> - <?php echo e($account->parent->label); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php switch($account->account_type):
                                        case ('classe'): ?> bg-primary-100 text-primary-800 <?php break; ?>
                                        <?php case ('sous_classe'): ?> bg-indigo-100 text-indigo-800 <?php break; ?>
                                        <?php case ('compte'): ?> bg-green-100 text-green-800 <?php break; ?>
                                        <?php case ('sous_compte'): ?> bg-yellow-100 text-yellow-800 <?php break; ?>
                                        <?php default: ?> bg-gray-100 text-gray-800
                                    <?php endswitch; ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $account->account_type))); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php echo e($account->account_nature === 'debit' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'); ?>">
                                    <?php echo e(ucfirst($account->account_nature)); ?>

                                </span>
                                <?php if($account->is_auxiliary): ?>
                                    <div class="text-xs text-gray-500 mt-1"><?php echo e(ucfirst($account->aux_type)); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($account->company->name); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php echo e($account->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($account->is_active ? 'Actif' : 'Inactif'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="<?php echo e(route('accounting.chart-of-accounts.show', $account)); ?>" 
                                       class="text-primary-600 hover:text-primary-900" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('accounting.chart-of-accounts.edit', $account)); ?>" 
                                       class="text-green-600 hover:text-green-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?php echo e(route('accounting.chart-of-accounts.create', ['parent_id' => $account->id])); ?>" 
                                       class="text-purple-600 hover:text-purple-900" title="Ajouter un sous-compte">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                Aucun compte trouvé
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if($accounts->hasPages()): ?>
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                <?php echo e($accounts->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>
    </div>

    <!-- Actions en lot -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Actions en lot</h2>
        <div class="flex space-x-4">
            <form method="POST" action="<?php echo e(route('accounting.chart-of-accounts.syscohada')); ?>" class="inline" id="syscohada-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="company_id" value="<?php echo e(request('company_id')); ?>" id="syscohada-company-id">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition <?php echo e(!request('company_id') ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                        onclick="return confirm('Créer le plan comptable SYSCOHADA de base ?')"
                        <?php echo e(!request('company_id') ? 'disabled' : ''); ?>>
                    <i class="fas fa-magic mr-2"></i>Créer Plan SYSCOHADA
                </button>
            </form>
            <a href="<?php echo e(route('accounting.chart-of-accounts.export', ['company_id' => request('company_id')])); ?>" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition <?php echo e(!request('company_id') ? 'opacity-50 cursor-not-allowed' : ''); ?>">
                <i class="fas fa-download mr-2"></i>Exporter Excel
            </a>
            <a href="<?php echo e(route('accounting.chart-of-accounts.export', ['company_id' => request('company_id'), 'format' => 'sage'])); ?>" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition <?php echo e(!request('company_id') ? 'opacity-50 cursor-not-allowed' : ''); ?>">
                <i class="fas fa-file-export mr-2"></i>Export SAGE
            </a>
        </div>
        <?php if(!request('company_id')): ?>
            <div class="mt-4 text-sm text-yellow-600">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Veuillez sélectionner une société dans les filtres ci-dessus pour activer les actions en lot.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update the company_id in the syscohada form when filters are applied
    const filterForm = document.querySelector('form[method="GET"]');
    const syscohadaForm = document.getElementById('syscohada-form');
    const syscohadaCompanyIdInput = document.getElementById('syscohada-company-id');
    
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const companyIdSelect = this.querySelector('select[name="company_id"]');
            if (companyIdSelect && syscohadaCompanyIdInput) {
                syscohadaCompanyIdInput.value = companyIdSelect.value;
            }
        });
    }
    
    // Prevent form submission if no company is selected
    if (syscohadaForm) {
        syscohadaForm.addEventListener('submit', function(e) {
            if (!syscohadaCompanyIdInput.value) {
                e.preventDefault();
                alert('Veuillez sélectionner une société avant de créer le plan SYSCOHADA.');
                return false;
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/accounting/chart-of-accounts/index.blade.php ENDPATH**/ ?>