

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Plan Comptable - Vue Arbre</h1>
                <p class="text-gray-600 mt-1">Structure hiérarchique du plan comptable</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('accounting.chart-of-accounts.index')); ?>" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-list mr-2"></i>Vue Liste
                </a>
                <a href="<?php echo e(route('accounting.chart-of-accounts.create')); ?>" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Nouveau Compte
                </a>
            </div>
        </div>
    </div>

    <!-- Sélection de la société -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="flex items-end space-x-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sélectionner une société</label>
                <select name="company_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500" onchange="this.form.submit()">
                    <option value="">Choisir une société</option>
                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($company->id); ?>" <?php echo e(request('company_id') == $company->id ? 'selected' : ''); ?>><?php echo e($company->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <?php if(request('company_id')): ?>
                <div>
                    <a href="<?php echo e(route('accounting.chart-of-accounts.export', ['company_id' => request('company_id')])); ?>" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-download mr-2"></i>Exporter
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Arbre du plan comptable -->
    <?php if(isset($company) && isset($accountsTree)): ?>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Plan Comptable de <?php echo e($company->name); ?></h2>
                <span class="text-sm text-gray-500"><?php echo e(count($accountsTree)); ?> comptes principaux</span>
            </div>

            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <?php if(count($accountsTree) > 0): ?>
                    <div class="tree-view">
                        <?php echo $__env->make('accounting.chart-of-accounts.partials.tree-node', ['nodes' => $accountsTree, 'level' => 0], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                <?php else: ?>
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-sitemap text-4xl mb-4"></i>
                        <p>Aucun compte trouvé pour cette société</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif(isset($companies) && count($companies) > 0 && !request('company_id')): ?>
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-sitemap text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-medium text-gray-700 mb-2">Sélectionnez une société</h3>
            <p class="text-gray-500">Choisissez une société dans le menu déroulant pour afficher son plan comptable</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-exclamation-circle text-5xl text-yellow-500 mb-4"></i>
            <h3 class="text-xl font-medium text-gray-700 mb-2">Aucune société trouvée</h3>
            <p class="text-gray-500">Aucune société n'est disponible dans le système</p>
            <a href="<?php echo e(route('companies.create')); ?>" class="mt-4 inline-block bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-plus mr-2"></i>Créer une société
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Partial view for tree nodes -->
<?php if(!View::exists('accounting.chart-of-accounts.partials.tree-node')): ?>
    <?php $__env->startPush('styles'); ?>
    <style>
        .tree-node {
            border-left: 1px solid #e5e7eb;
            margin-left: 1rem;
            padding-left: 1rem;
        }
        .tree-node-header {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
        }
        .tree-node-toggle {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
            cursor: pointer;
        }
        .tree-node-content {
            margin-left: 1.5rem;
        }
        .tree-node-children {
            display: none;
        }
        .tree-node-children.expanded {
            display: block;
        }
    </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle tree nodes
            document.querySelectorAll('.tree-node-toggle').forEach(function(toggle) {
                toggle.addEventListener('click', function() {
                    const children = this.closest('.tree-node').querySelector('.tree-node-children');
                    if (children) {
                        children.classList.toggle('expanded');
                        const icon = this.querySelector('i');
                        if (icon) {
                            if (children.classList.contains('expanded')) {
                                icon.classList.remove('fa-chevron-right');
                                icon.classList.add('fa-chevron-down');
                            } else {
                                icon.classList.remove('fa-chevron-down');
                                icon.classList.add('fa-chevron-right');
                            }
                        }
                    }
                });
            });
        });
    </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\chart-of-accounts\tree.blade.php ENDPATH**/ ?>