<?php $__env->startSection('title', 'Détails du Mouvement de Stock'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gradient-to-r from-blue-600 to-blue-500 text-white px-6 py-5">
            <h2 class="text-xl font-semibold mb-3 sm:mb-0">Détails du Mouvement de Stock</h2>
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo e(route('stock.movements.index')); ?>" 
                   class="flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition font-medium">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <?php if(!$movement->validated_by && auth()->user()->can('validate', $movement)): ?>
                <form action="<?php echo e(route('stock.movements.validate', $movement)); ?>" method="POST" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir valider ce mouvement?')">
                    <?php echo csrf_field(); ?>
                    <button type="submit" 
                            class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium">
                        <i class="fas fa-check"></i> Valider
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="p-6 space-y-8">
            <!-- Informations principales -->
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Bloc 1 -->
                <div class="border rounded-xl shadow-sm">
                    <div class="bg-blue-600 text-white px-4 py-3 font-semibold rounded-t-xl">
                        Informations Générales
                    </div>
                    <div class="p-5">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Référence :</span>
                                <span class="text-gray-800"><?php echo e($movement->reference ?: 'Non spécifiée'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Type :</span>
                                <?php if($movement->type == 'entree'): ?>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-semibold">Entrée</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-sm font-semibold">Sortie</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Date :</span>
                                <span><?php echo e($movement->created_at->format('d/m/Y H:i')); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Statut :</span>
                                <?php if($movement->validated_by): ?>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-semibold">Validé</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-sm font-semibold">En attente</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Créé par :</span>
                                <span><?php echo e($movement->createdBy->name ?? 'Utilisateur inconnu'); ?></span>
                            </div>
                            <?php if($movement->validated_by): ?>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Validé par :</span>
                                <span><?php echo e($movement->validatedBy->name ?? 'Utilisateur inconnu'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Date validation :</span>
                                <span><?php echo e($movement->validated_at ? $movement->validated_at->format('d/m/Y H:i') : 'N/A'); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Bloc 2 -->
                <div class="border rounded-xl shadow-sm">
                    <div class="bg-indigo-600 text-white px-4 py-3 font-semibold rounded-t-xl">
                        Détails du Produit et Dépôt
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Produit :</span>
                            <span><?php echo e($movement->product->name ?? 'N/A'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Dépôt :</span>
                            <span><?php echo e($movement->warehouse->nom ?? 'N/A'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Quantité :</span>
                            <span><?php echo e(number_format($movement->quantite, 0, ',', ' ')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Prix Unitaire :</span>
                            <span><?php echo e(number_format($movement->prix_unitaire, 0, ',', ' ')); ?></span>
                        </div>
                        <!-- <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Montant Total :</span>
                            <span><?php echo e(number_format($movement->montant_total, 2)); ?></span>
                        </div> -->
                    </div>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="border rounded-xl shadow-sm">
                <div class="bg-green-600 text-white px-4 py-3 font-semibold rounded-t-xl">
                    Informations Complémentaires
                </div>
                <div class="p-5 space-y-5">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Motif</label>
                        <textarea class="w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-800 focus:ring-2 focus:ring-green-400 focus:border-green-400 transition resize-none" rows="3" readonly>
                            <?php echo e($movement->motif ?: 'Aucun motif spécifié'); ?>

                        </textarea>
                    </div>

                    <?php if($movement->sourceEntity || $movement->destinationEntity): ?>
                    <div class="grid md:grid-cols-2 gap-4">
                        <?php if($movement->sourceEntity): ?>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Source</label>
                            <input type="text" readonly 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-800 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition" 
                                   value="<?php echo e(class_basename($movement->sourceEntity)); ?>: <?php echo e($movement->sourceEntity->nom ?? $movement->sourceEntity->reference ?? 'N/A'); ?>">
                        </div>
                        <?php endif; ?>
                        <?php if($movement->destinationEntity): ?>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Destination</label>
                            <input type="text" readonly 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-800 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition" 
                                   value="<?php echo e(class_basename($movement->destinationEntity)); ?>: <?php echo e($movement->destinationEntity->nom ?? $movement->destinationEntity->reference ?? 'N/A'); ?>">
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\movements\show.blade.php ENDPATH**/ ?>