

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Gestion des Fiches de Paie</h2>
            <nav class="text-gray-500 text-sm mt-1">
                <ol class="flex space-x-2">
                    <li><a href="<?php echo e(route('dashboard')); ?>" class="hover:underline">Tableau de bord</a></li>
                    <li>/</li>
                    <li>Fiches de paie</li>
                </ol>
            </nav>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="<?php echo e(route('hr.payslips.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow">
                <i class="mdi mdi-plus-circle mr-2"></i> Générer Fiche de Paie
            </a>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="flex flex-col md:flex-row md:justify-between mb-4 space-y-4 md:space-y-0">
        <form method="GET" class="flex-1 md:mr-4 flex items-center space-x-2">
            <input type="text" name="search" placeholder="Rechercher un employé..." value="<?php echo e(request('search')); ?>" 
                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="mdi mdi-magnify"></i>
            </button>
        </form>

        <div class="flex space-x-2">
            <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="">Tous les statuts</option>
                <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Brouillon</option>
                <option value="validated" <?php echo e(request('status') == 'validated' ? 'selected' : ''); ?>>Validé</option>
                <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Payé</option>
            </select>

            <select name="month" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="">Tous les mois</option>
                <?php for($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo e($i); ?>" <?php echo e(request('month') == $i ? 'selected' : ''); ?>>
                        <?php echo e(DateTime::createFromFormat('!m', $i)->format('F')); ?>

                    </option>
                <?php endfor; ?>
            </select>

            <select name="year" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="">Toutes les années</option>
                <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                    <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>

    <!-- Payslips Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Employé</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Salaire Brut</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Salaire Net</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Génération</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $payslips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payslip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 flex items-center space-x-2">
                            <img src="<?php echo e($payslip->employee->photo ? asset('storage/' . $payslip->employee->photo) : asset('images/users/avatar-default.jpg')); ?>" 
                                 alt="Employee" class="w-8 h-8 rounded-full object-cover">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-800"><?php echo e($payslip->employee->prenom); ?> <?php echo e($payslip->employee->nom); ?></span>
                                <span class="text-gray-500 text-sm"><?php echo e($payslip->employee->currentPosition->title ?? 'N/A'); ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 inline-flex text-xs font-semibold rounded-full <?php echo e($payslip->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : ($payslip->status == 'validated' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800')); ?>">
                                <?php echo e($payslip->month); ?>/<?php echo e($payslip->year); ?>

                            </span>
                        </td>
                        <td class="px-4 py-2"><?php echo e(number_format($payslip->gross_salary, 0, ',', ' ')); ?> FCFA</td>
                        <td class="px-4 py-2"><?php echo e(number_format($payslip->net_salary, 0, ',', ' ')); ?> FCFA</td>
                        <td class="px-4 py-2">
                            <?php if($payslip->status == 'draft'): ?>
                                <span class="px-2 inline-flex text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Brouillon</span>
                            <?php elseif($payslip->status == 'validated'): ?>
                                <span class="px-2 inline-flex text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Validé</span>
                            <?php elseif($payslip->status == 'paid'): ?>
                                <span class="px-2 inline-flex text-xs font-semibold bg-green-100 text-green-800 rounded-full">Payé</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2"><?php echo e($payslip->created_at->format('d/m/Y')); ?></td>
                        <td class="px-4 py-2 text-center">
                            <div class="relative inline-block text-left">
                                <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50" id="menu-button-<?php echo e($payslip->id); ?>" aria-expanded="true" aria-haspopup="true">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden menu-<?php echo e($payslip->id); ?>">
                                    <div class="py-1">
                                        <a href="<?php echo e(route('hr.payslips.show', $payslip)); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Voir</a>
                                        <a href="<?php echo e(route('hr.payslips.download', $payslip)); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Télécharger</a>
                                        <?php if($payslip->status == 'draft'): ?>
                                            <a href="<?php echo e(route('hr.payslips.edit', $payslip)); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Modifier</a>
                                            <form action="<?php echo e(route('hr.payslips.validate', $payslip)); ?>" method="POST" class="block">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Valider</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if($payslip->status == 'validated'): ?>
                                            <form action="<?php echo e(route('hr.payslips.pay', $payslip)); ?>" method="POST" class="block">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Marquer comme Payé</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if($payslip->status == 'draft'): ?>
                                            <form action="<?php echo e(route('hr.payslips.destroy', $payslip)); ?>" method="POST" class="block">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette fiche de paie ?')">Supprimer</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-8">
                            <img src="<?php echo e(asset('images/undraw_empty.svg')); ?>" alt="Aucune donnée" class="mx-auto mb-4" style="max-height: 200px;">
                            <h4 class="text-lg font-medium text-gray-700">Aucune fiche de paie trouvée</h4>
                            <p class="text-gray-500 mb-4">Commencez par générer une fiche de paie pour vos employés.</p>
                            <a href="<?php echo e(route('hr.payslips.create')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Générer une Fiche de Paie</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <?php echo e($payslips->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\payslips\index.blade.php ENDPATH**/ ?>