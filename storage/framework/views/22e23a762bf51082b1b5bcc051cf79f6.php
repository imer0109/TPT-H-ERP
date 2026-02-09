

<?php $__env->startSection('title', 'Évaluations du fournisseur - ' . $fournisseur->raison_sociale); ?>

<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Évaluations du fournisseur: <?php echo e($fournisseur->raison_sociale); ?>

                    </h2>
                    <a href="<?php echo e(route('fournisseurs.ratings.create', $fournisseur)); ?>" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Nouvelle Évaluation
                    </a>
                </div>

                <!-- Supplier Info -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Code Fournisseur</p>
                            <p class="font-semibold"><?php echo e($fournisseur->code_fournisseur); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Note Moyenne</p>
                            <p class="font-semibold">
                                <?php if($fournisseur->note_moyenne): ?>
                                    <span class="text-<?php echo e($fournisseur->averageRating->ratingColor); ?>-500">
                                        <?php echo e(number_format($fournisseur->note_moyenne, 2)); ?>/5
                                    </span>
                                <?php else: ?>
                                    Non évalué
                                <?php endif; ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nombre d'évaluations</p>
                            <p class="font-semibold"><?php echo e($fournisseur->nombre_evaluations); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Auto Evaluate Button -->
                <div class="mb-6">
                    <button id="autoEvaluateBtn" 
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                            data-supplier-id="<?php echo e($fournisseur->id); ?>">
                        Évaluer Automatiquement
                    </button>
                </div>

                <!-- Ratings Table -->
                <?php if($ratings->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Qualité
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Livraison
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Réactivité
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Prix
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Note Globale
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Évaluateur
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($rating->evaluation_date->format('d/m/Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <svg class="w-5 h-5 <?php echo e($i <= $rating->quality_rating ? 'text-yellow-400' : 'text-gray-300'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <svg class="w-5 h-5 <?php echo e($i <= $rating->delivery_rating ? 'text-yellow-400' : 'text-gray-300'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <svg class="w-5 h-5 <?php echo e($i <= $rating->responsiveness_rating ? 'text-yellow-400' : 'text-gray-300'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <svg class="w-5 h-5 <?php echo e($i <= $rating->pricing_rating ? 'text-yellow-400' : 'text-gray-300'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <div class="flex">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <svg class="w-5 h-5 <?php echo e($i <= $rating->star_rating ? 'text-yellow-400' : 'text-gray-300'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="ml-2"><?php echo e(number_format($rating->overall_score, 2)); ?>/5</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php if($rating->evaluator): ?>
                                        <?php echo e($rating->evaluator->name); ?>

                                    <?php else: ?>
                                        Système
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="<?php echo e(route('fournisseurs.ratings.show', [$fournisseur, $rating])); ?>" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                    <?php if($rating->evaluated_by): ?>
                                    <a href="<?php echo e(route('fournisseurs.ratings.edit', [$fournisseur, $rating])); ?>" 
                                       class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                                    <form action="<?php echo e(route('fournisseurs.ratings.destroy', [$fournisseur, $rating])); ?>" 
                                          method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette évaluation?')">
                                            Supprimer
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    <?php echo e($ratings->links()); ?>

                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <p class="text-gray-500">Aucune évaluation trouvée pour ce fournisseur.</p>
                    <a href="<?php echo e(route('fournisseurs.ratings.create', $fournisseur)); ?>" 
                       class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Créer une première évaluation
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Auto Evaluation Script -->
<script>
document.getElementById('autoEvaluateBtn').addEventListener('click', function() {
    const supplierId = this.getAttribute('data-supplier-id');
    
    fetch(`/fournisseurs/${supplierId}/ratings/auto-evaluate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Évaluation automatique effectuée avec succès!');
            location.reload();
        } else {
            alert('Erreur lors de l\'évaluation automatique.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'évaluation automatique.');
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\ratings\index.blade.php ENDPATH**/ ?>