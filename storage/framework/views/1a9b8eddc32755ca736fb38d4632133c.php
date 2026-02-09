<?php $__env->startSection('title', 'Détails de la Réclamation'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-xl">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h4 class="text-lg sm:text-xl font-semibold text-gray-800">Détails de la Réclamation #<?php echo e($reclamation->id); ?></h4>
            <a href="<?php echo e(route('clients.reclamations.index')); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Client</label>
                        <p><a href="<?php echo e(route('clients.show', $reclamation->client)); ?>" class="text-blue-600 hover:text-blue-700 hover:underline"><?php echo e($reclamation->client->nom_raison_sociale); ?></a></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type de Réclamation</label>
                        <?php
                            $type = $reclamation->type_reclamation;
                            $typeColor = $type === 'produit_defectueux' ? 'bg-red-100 text-red-700' :
                                         ($type === 'retard_livraison' ? 'bg-yellow-100 text-yellow-700' :
                                         ($type === 'erreur_facturation' ? 'bg-sky-100 text-sky-700' : 'bg-gray-100 text-gray-700'));
                        ?>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold <?php echo e($typeColor); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $type))); ?>

                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        <?php
                            $statut = $reclamation->statut;
                            $statutColor = $statut === 'ouverte' ? 'bg-yellow-100 text-yellow-700' :
                                           ($statut === 'en_cours' ? 'bg-sky-100 text-sky-700' : 'bg-green-100 text-green-700');
                        ?>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold <?php echo e($statutColor); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $statut))); ?>

                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Agent Assigné</label>
                        <p><?php echo e($reclamation->agent ? $reclamation->agent->nom . ' ' . $reclamation->agent->prenom : 'Non assigné'); ?></p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de Création</label>
                        <p><?php echo e($reclamation->created_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de Résolution</label>
                        <p><?php echo e($reclamation->date_resolution ? $reclamation->date_resolution->format('d/m/Y H:i') : 'Non résolue'); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dernière Mise à Jour</label>
                        <p><?php echo e($reclamation->updated_at->format('d/m/Y H:i')); ?></p>
                    </div>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p class="text-gray-700"><?php echo e($reclamation->description); ?></p>
                </div>

                <?php if($reclamation->solution): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Solution</label>
                    <p class="text-gray-700"><?php echo e($reclamation->solution); ?></p>
                </div>
                <?php endif; ?>

                <?php if($reclamation->commentaires): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Commentaires</label>
                    <p class="text-gray-700"><?php echo e($reclamation->commentaires); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <?php if($reclamation->documents->count() > 0): ?>
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700">Documents joints</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mt-2">
                    <?php $__currentLoopData = $reclamation->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('documents.download', $document)); ?>" class="inline-flex items-center gap-2 px-3 py-2 border border-blue-300 text-blue-700 rounded-md hover:bg-blue-50">
                        <i class="fas fa-file"></i> <?php echo e($document->nom); ?>

                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="mt-6 flex flex-wrap items-center gap-3">
                <a href="<?php echo e(route('clients.reclamations.edit', $reclamation)); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md">
                    <i class="fas fa-edit"></i> <span>Modifier</span>
                </a>
                <form action="<?php echo e(route('clients.reclamations.destroy', $reclamation)); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')">
                        <i class="fas fa-trash"></i> <span>Supprimer</span>
                    </button>
                </form>

                <form action="<?php echo e(route('clients.reclamations.change-status', $reclamation)); ?>" method="POST" class="inline-flex items-center gap-2">
                    <?php echo csrf_field(); ?>
                    <span class="text-gray-600">Changer le statut:</span>
                    <button type="submit" name="statut" value="ouverte" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md text-sm">Ouverte</button>
                    <button type="submit" name="statut" value="en_cours" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-md text-sm">En Cours</button>
                    <button type="submit" name="statut" value="resolue" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm">Résolue</button>
                </form>

                <form action="<?php echo e(route('clients.reclamations.assign-agent', $reclamation)); ?>" method="POST" class="inline-flex items-center gap-2">
                    <?php echo csrf_field(); ?>
                    <span class="text-gray-600">Assigner un agent:</span>
                    <select name="agent_id" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="">Sélectionnez un agent</option>
                        <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($agent->id); ?>"><?php echo e($agent->nom); ?> <?php echo e($agent->prenom); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md text-sm">Assigner</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\reclamations\show.blade.php ENDPATH**/ ?>