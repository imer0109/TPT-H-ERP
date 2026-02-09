

<?php $__env->startSection('title', 'Détails de l\'évaluation - ' . $rating->fournisseur->raison_sociale); ?>

<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Détails de l'évaluation
                    </h2>
                    <div>
                        <a href="<?php echo e(route('fournisseurs.ratings.index', $rating->fournisseur)); ?>" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Retour
                        </a>
                        <?php if($rating->evaluated_by): ?>
                        <a href="<?php echo e(route('fournisseurs.ratings.edit', [$rating->fournisseur, $rating])); ?>" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Modifier
                        </a>
                        <form action="<?php echo e(route('fournisseurs.ratings.destroy', [$rating->fournisseur, $rating])); ?>" 
                              method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette évaluation?')">
                                Supprimer
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Rating Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Supplier Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Informations du fournisseur</h3>
                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-gray-600">Raison Sociale</p>
                                <p class="font-semibold"><?php echo e($rating->fournisseur->raison_sociale); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Code Fournisseur</p>
                                <p class="font-semibold"><?php echo e($rating->fournisseur->code_fournisseur); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Note Moyenne</p>
                                <p class="font-semibold">
                                    <?php if($rating->fournisseur->note_moyenne): ?>
                                        <span class="text-<?php echo e($rating->fournisseur->averageRating->ratingColor); ?>-500">
                                            <?php echo e(number_format($rating->fournisseur->note_moyenne, 2)); ?>/5
                                        </span>
                                    <?php else: ?>
                                        Non évalué
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluation Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Informations de l'évaluation</h3>
                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-gray-600">Date d'évaluation</p>
                                <p class="font-semibold"><?php echo e($rating->evaluation_date->format('d/m/Y')); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Évaluateur</p>
                                <p class="font-semibold">
                                    <?php if($rating->evaluator): ?>
                                        <?php echo e($rating->evaluator->name); ?>

                                    <?php else: ?>
                                        Système (Évaluation automatique)
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Note globale</p>
                                <p class="font-semibold">
                                    <span class="text-<?php echo e($rating->ratingColor); ?>-500">
                                        <?php echo e(number_format($rating->overall_score, 2)); ?>/5
                                    </span>
                                    (<?php echo e($rating->ratingDescription); ?>)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating Categories -->
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Détail des notes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Quality Rating -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-2">Qualité</h4>
                            <div class="flex mb-2">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <svg class="w-6 h-6 <?php echo e($i <= $rating->quality_rating ? 'text-yellow-400' : 'text-gray-300'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <p class="text-sm text-gray-600"><?php echo e($rating->quality_rating); ?>/5</p>
                        </div>

                        <!-- Delivery Rating -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-2">Livraison</h4>
                            <div class="flex mb-2">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <svg class="w-6 h-6 <?php echo e($i <= $rating->delivery_rating ? 'text-yellow-400' : 'text-gray-300'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <p class="text-sm text-gray-600"><?php echo e($rating->delivery_rating); ?>/5</p>
                        </div>

                        <!-- Responsiveness Rating -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-2">Réactivité</h4>
                            <div class="flex mb-2">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <svg class="w-6 h-6 <?php echo e($i <= $rating->responsiveness_rating ? 'text-yellow-400' : 'text-gray-300'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <p class="text-sm text-gray-600"><?php echo e($rating->responsiveness_rating); ?>/5</p>
                        </div>

                        <!-- Pricing Rating -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-2">Prix</h4>
                            <div class="flex mb-2">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <svg class="w-6 h-6 <?php echo e($i <= $rating->pricing_rating ? 'text-yellow-400' : 'text-gray-300'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <p class="text-sm text-gray-600"><?php echo e($rating->pricing_rating); ?>/5</p>
                        </div>
                    </div>
                </div>

                <!-- Comments -->
                <?php if($rating->comments): ?>
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Commentaires</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700"><?php echo e($rating->comments); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\ratings\show.blade.php ENDPATH**/ ?>