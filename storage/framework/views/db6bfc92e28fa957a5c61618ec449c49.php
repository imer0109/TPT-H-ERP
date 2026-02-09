

<?php $__env->startSection('title', 'Réclamations Fournisseur'); ?>
<?php $__env->startSection('header', 'Réclamations'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-gray-50 py-8">
    <div class="mx-auto max-w-7xl px-4 space-y-6">

        <!-- HEADER -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl font-semibold text-gray-800">Réclamations</h2>

            <a href="<?php echo e(route('supplier.portal.create-issue')); ?>"
               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700">
                <i class="fas fa-plus"></i>
                Nouvelle réclamation
            </a>
        </div>

        <!-- FILTRES -->
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <form method="GET" action="<?php echo e(route('supplier.portal.issues')); ?>"
                  class="flex flex-col gap-3 md:flex-row md:items-end">

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">Statut</label>
                    <select name="status" class="input">
                        <option value="">Tous</option>
                        <option value="open" <?php if(request('status') === 'open'): echo 'selected'; endif; ?>>Ouverte</option>
                        <option value="in_progress" <?php if(request('status') === 'in_progress'): echo 'selected'; endif; ?>>En cours</option>
                        <option value="resolved" <?php if(request('status') === 'resolved'): echo 'selected'; endif; ?>>Résolue</option>
                        <option value="closed" <?php if(request('status') === 'closed'): echo 'selected'; endif; ?>>Fermée</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">Type</label>
                    <select name="type" class="input">
                        <option value="">Tous</option>
                        <option value="retard" <?php if(request('type') === 'retard'): echo 'selected'; endif; ?>>Retard</option>
                        <option value="produit_non_conforme" <?php if(request('type') === 'produit_non_conforme'): echo 'selected'; endif; ?>>Produit non conforme</option>
                        <option value="erreur_facturation" <?php if(request('type') === 'erreur_facturation'): echo 'selected'; endif; ?>>Erreur de facturation</option>
                        <option value="autre" <?php if(request('type') === 'autre'): echo 'selected'; endif; ?>>Autre</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button class="btn-secondary">
                        <i class="fas fa-filter mr-1"></i> Filtrer
                    </button>

                    <a href="<?php echo e(route('supplier.portal.issues')); ?>" class="btn-light">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="rounded-xl bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left">Titre</th>
                            <th class="px-6 py-3 text-left">Type</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Statut</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $issues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    <?php echo e($issue->titre); ?>

                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $issue->type))); ?>

                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?php echo e($issue->created_at->format('d/m/Y H:i')); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                        $statusClasses = [
                                            'open' => 'bg-blue-100 text-blue-700',
                                            'in_progress' => 'bg-yellow-100 text-yellow-700',
                                            'resolved' => 'bg-green-100 text-green-700',
                                            'closed' => 'bg-gray-200 text-gray-700',
                                        ];
                                    ?>

                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium <?php echo e($statusClasses[$issue->statut] ?? 'bg-gray-100 text-gray-600'); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $issue->statut))); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="<?php echo e(route('supplier.portal.issues.show', $issue)); ?>"
                                       class="text-blue-600 hover:underline text-sm font-medium">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500">
                                    Aucune réclamation trouvée
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="border-t bg-gray-50 px-6 py-3">
                <?php echo e($issues->withQueryString()->links()); ?>

            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\portal\issues.blade.php ENDPATH**/ ?>