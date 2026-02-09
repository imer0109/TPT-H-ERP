

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Évaluations</h1>

        <a href="<?php echo e(route('hr.evaluations.create')); ?>"
           class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
            <i class="mdi mdi-plus-circle mr-2 text-lg"></i>
            Nouvelle Évaluation
        </a>
    </div>

    <!-- FILTERS -->
    <div class="bg-white p-5 rounded-lg shadow mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Search -->
            <input type="text" name="search" placeholder="Rechercher un employé..."
                   value="<?php echo e(request('search')); ?>"
                   class="col-span-1 md:col-span-4 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">

            <!-- Status -->
            <select name="status" onchange="this.form.submit()"
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                <option value="">Tous les statuts</option>
                <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Brouillon</option>
                <option value="submitted" <?php echo e(request('status') == 'submitted' ? 'selected' : ''); ?>>Soumise</option>
                <option value="acknowledged" <?php echo e(request('status') == 'acknowledged' ? 'selected' : ''); ?>>Reconnue</option>
                <option value="disputed" <?php echo e(request('status') == 'disputed' ? 'selected' : ''); ?>>Contestée</option>
            </select>

            <!-- Period -->
            <select name="period" onchange="this.form.submit()"
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                <option value="">Toutes les périodes</option>
                <option value="Q1" <?php echo e(request('period') == 'Q1' ? 'selected' : ''); ?>>T1 <?php echo e(date('Y')); ?></option>
                <option value="Q2" <?php echo e(request('period') == 'Q2' ? 'selected' : ''); ?>>T2 <?php echo e(date('Y')); ?></option>
                <option value="Q3" <?php echo e(request('period') == 'Q3' ? 'selected' : ''); ?>>T3 <?php echo e(date('Y')); ?></option>
                <option value="Q4" <?php echo e(request('period') == 'Q4' ? 'selected' : ''); ?>>T4 <?php echo e(date('Y')); ?></option>
            </select>

            <!-- Year -->
            <select name="year" onchange="this.form.submit()"
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                <option value="">Toutes les années</option>
                <?php for($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
                    <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                <?php endfor; ?>
            </select>
        </form>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Employé</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Période</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Type</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-700">Note Globale</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Statut</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Créée le</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                <?php $__empty_1 = true; $__currentLoopData = $evaluations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evaluation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <!-- Employee -->
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">
                            <?php echo e($evaluation->employee->full_name); ?>

                        </div>
                        <div class="text-sm text-gray-500">
                            <?php echo e($evaluation->employee->currentPosition->title ?? 'N/A'); ?>

                        </div>
                    </td>

                    <!-- Period -->
                    <td class="px-6 py-4 text-gray-700">
                        <?php echo e($evaluation->period); ?>

                    </td>

                    <!-- Evaluation Type -->
                    <td class="px-6 py-4 text-gray-700">
                        <?php echo e($evaluation->evaluation_type_text); ?>

                    </td>

                    <!-- Score -->
                    <td class="px-6 py-4 text-right">
                        <?php if($evaluation->overall_score): ?>
                        <span class="px-3 py-1 text-xs rounded-full text-white bg-<?php echo e($evaluation->overall_rating_color); ?>-600">
                            <?php echo e($evaluation->overall_score); ?>/5 – <?php echo e($evaluation->overall_rating_text); ?>

                        </span>
                        <?php else: ?>
                        <span class="text-gray-500">Non évalué</span>
                        <?php endif; ?>
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-<?php echo e($evaluation->status_color); ?>-100 text-<?php echo e($evaluation->status_color); ?>-700">
                            <?php echo e($evaluation->status_text); ?>

                        </span>
                    </td>

                    <!-- Date -->
                    <td class="px-6 py-4 text-gray-700">
                        <?php echo e($evaluation->created_at->format('d/m/Y')); ?>

                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center items-center space-x-2">
                            <!-- View Button (Always visible) -->
                            <a href="<?php echo e(route('hr.evaluations.show', $evaluation)); ?>"
                               class="text-blue-600 hover:text-blue-800 text-lg p-1"
                               title="Voir">
                                <i class="mdi mdi-eye"></i>
                            </a>

                            <!-- Edit Button (Only for draft evaluations) -->
                            <?php if($evaluation->isDraft()): ?>
                            <a href="<?php echo e(route('hr.evaluations.edit', $evaluation)); ?>"
                               class="text-indigo-600 hover:text-indigo-800 text-lg p-1"
                               title="Modifier">
                                <i class="mdi mdi-pencil"></i>
                            </a>

                            <!-- Delete Button (Only for draft evaluations) -->
                            <form action="<?php echo e(route('hr.evaluations.destroy', $evaluation)); ?>" 
                                  method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" onclick="return confirm('Supprimer cette évaluation ?')" 
                                        class="text-red-600 hover:text-red-800 text-lg p-1"
                                        title="Supprimer">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                            <?php endif; ?>

                            <!-- Acknowledge Button (Only for submitted evaluations) -->
                            <?php if($evaluation->isSubmitted()): ?>
                            <form action="<?php echo e(route('hr.evaluations.acknowledge', $evaluation)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-green-600 hover:text-green-800 text-lg p-1" title="Confirmer">
                                    <i class="mdi mdi-check-circle"></i>
                                </button>
                            </form>
                            <?php endif; ?>

                            <!-- Dispute Button (Only for submitted evaluations) -->
                            <?php if($evaluation->isSubmitted()): ?>
                            <form action="<?php echo e(route('hr.evaluations.dispute', $evaluation)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-orange-600 hover:text-orange-800 text-lg p-1" title="Contester">
                                    <i class="mdi mdi-alert-circle"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="py-12 text-center">
                        <img src="<?php echo e(asset('images/undraw_empty.svg')); ?>" class="mx-auto mb-4 w-48">
                        <h3 class="text-lg font-semibold text-gray-800">Aucune évaluation trouvée</h3>
                        <p class="text-gray-500 mb-4">Commencez par créer une nouvelle évaluation.</p>
                        <a href="<?php echo e(route('hr.evaluations.create')); ?>"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Créer une Évaluation
                        </a>
                    </td>
                </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-6">
        <?php echo e($evaluations->links()); ?>

    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\evaluations\index.blade.php ENDPATH**/ ?>