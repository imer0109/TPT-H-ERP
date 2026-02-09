<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Détails de la Réclamation #<?php echo e($reclamation->id); ?></h1>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('client-reclamations.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
            <a href="<?php echo e(route('client-reclamations.edit', $reclamation)); ?>" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informations de la réclamation</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Client</p>
                        <p class="text-base font-semibold">
                            <a href="<?php echo e(route('clients.show', $reclamation->client)); ?>" class="text-red-600 hover:text-red-800">
                                <?php echo e($reclamation->client->nom_raison_sociale); ?> (<?php echo e($reclamation->client->code_client); ?>)
                            </a>
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Type de réclamation</p>
                        <p class="text-base">
                            <?php
                                $typeClasses = [
                                    'qualite_produit' => 'bg-orange-100 text-orange-800',
                                    'service_client' => 'bg-blue-100 text-blue-800',
                                    'livraison' => 'bg-purple-100 text-purple-800',
                                    'facturation' => 'bg-yellow-100 text-yellow-800',
                                    'autre' => 'bg-gray-100 text-gray-800'
                                ];
                                $typeLabels = [
                                    'qualite_produit' => 'Qualité produit',
                                    'service_client' => 'Service client',
                                    'livraison' => 'Livraison',
                                    'facturation' => 'Facturation',
                                    'autre' => 'Autre'
                                ];
                            ?>
                            <span class="px-2 py-1 text-xs rounded-full <?php echo e($typeClasses[$reclamation->type_reclamation] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e($typeLabels[$reclamation->type_reclamation] ?? ucfirst($reclamation->type_reclamation)); ?>

                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de création</p>
                        <p class="text-base"><?php echo e($reclamation->created_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Statut</p>
                        <p class="text-base">
                            <?php
                                $statusClasses = [
                                    'ouverte' => 'bg-red-100 text-red-800',
                                    'en_cours' => 'bg-yellow-100 text-yellow-800',
                                    'resolue' => 'bg-green-100 text-green-800',
                                    'fermee' => 'bg-gray-100 text-gray-800'
                                ];
                                $statusLabels = [
                                    'ouverte' => 'Ouverte',
                                    'en_cours' => 'En cours',
                                    'resolue' => 'Résolue',
                                    'fermee' => 'Fermée'
                                ];
                            ?>
                            <span class="px-2 py-1 text-xs rounded-full <?php echo e($statusClasses[$reclamation->statut] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e($statusLabels[$reclamation->statut] ?? ucfirst($reclamation->statut)); ?>

                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Agent assigné</p>
                        <p class="text-base">
                            <?php if($reclamation->agent): ?>
                                <?php echo e($reclamation->agent->nom); ?> <?php echo e($reclamation->agent->prenom); ?>

                            <?php else: ?>
                                <span class="text-gray-500 italic">Non assigné</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de résolution</p>
                        <p class="text-base">
                            <?php if($reclamation->date_resolution): ?>
                                <?php echo e(\Carbon\Carbon::parse($reclamation->date_resolution)->format('d/m/Y H:i')); ?>

                            <?php else: ?>
                                <span class="text-gray-500 italic">Non résolue</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-500">Description</p>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                        <p class="text-base whitespace-pre-line"><?php echo e($reclamation->description); ?></p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-500">Solution</p>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                        <?php if($reclamation->solution): ?>
                            <p class="text-base whitespace-pre-line"><?php echo e($reclamation->solution); ?></p>
                        <?php else: ?>
                            <p class="text-gray-500 italic">Aucune solution enregistrée</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-500">Commentaires</p>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                        <?php if($reclamation->commentaires): ?>
                            <p class="text-base whitespace-pre-line"><?php echo e($reclamation->commentaires); ?></p>
                        <?php else: ?>
                            <p class="text-gray-500 italic">Aucun commentaire</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Actions</h2>
                
                <div class="space-y-3">
                    <form action="<?php echo e(route('client-reclamations.change-status', $reclamation)); ?>" method="POST" class="w-full">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <div class="mb-2">
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Changer le statut</label>
                            <select name="statut" id="statut" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                                <option value="ouverte" <?php echo e($reclamation->statut == 'ouverte' ? 'selected' : ''); ?>>Ouverte</option>
                                <option value="en_cours" <?php echo e($reclamation->statut == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                                <option value="resolue" <?php echo e($reclamation->statut == 'resolue' ? 'selected' : ''); ?>>Résolue</option>
                                <option value="fermee" <?php echo e($reclamation->statut == 'fermee' ? 'selected' : ''); ?>>Fermée</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-check-circle mr-2"></i> Mettre à jour le statut
                        </button>
                    </form>
                    
                    <form action="<?php echo e(route('client-reclamations.assign-agent', $reclamation)); ?>" method="POST" class="w-full">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <div class="mb-2">
                            <label for="agent_id" class="block text-sm font-medium text-gray-700 mb-1">Assigner à un agent</label>
                            <select name="agent_id" id="agent_id" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                                <option value="">Sélectionner un agent</option>
                                <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($agent->id); ?>" <?php echo e($reclamation->agent_id == $agent->id ? 'selected' : ''); ?>>
                                        <?php echo e($agent->nom); ?> <?php echo e($agent->prenom); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-user-check mr-2"></i> Assigner l'agent
                        </button>
                    </form>
                    
                    <form action="<?php echo e(route('client-reclamations.destroy', $reclamation)); ?>" method="POST" class="w-full" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-trash-alt mr-2"></i> Supprimer la réclamation
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Documents -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Documents</h2>
                
                <?php if($reclamation->documents->count() > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $reclamation->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                <div class="flex items-center">
                                    <?php
                                        $icon = 'fa-file';
                                        if(in_array($document->format, ['jpg', 'jpeg', 'png', 'gif'])) {
                                            $icon = 'fa-file-image';
                                        } elseif(in_array($document->format, ['pdf'])) {
                                            $icon = 'fa-file-pdf';
                                        } elseif(in_array($document->format, ['doc', 'docx'])) {
                                            $icon = 'fa-file-word';
                                        } elseif(in_array($document->format, ['xls', 'xlsx'])) {
                                            $icon = 'fa-file-excel';
                                        }
                                    ?>
                                    <i class="fas <?php echo e($icon); ?> text-gray-500 mr-3 text-xl"></i>
                                    <div>
                                        <p class="text-sm font-medium"><?php echo e($document->nom); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo e(number_format($document->taille / 1024, 2)); ?> KB · <?php echo e(strtoupper($document->format)); ?></p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('documents.show', $document)); ?>" target="_blank" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('documents.download', $document)); ?>" class="text-green-500 hover:text-green-700">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="<?php echo e(route('documents.destroy', $document)); ?>" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 italic">Aucun document attaché</p>
                <?php endif; ?>
                
                <div class="mt-4">
                    <form action="<?php echo e(route('client-reclamations.upload-document', $reclamation)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-2">
                            <label for="document" class="block text-sm font-medium text-gray-700 mb-1">Ajouter un document</label>
                            <input type="file" name="document" id="document" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                        <div class="mb-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <input type="text" name="description" id="description" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-upload mr-2"></i> Télécharger
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\client-reclamations\show.blade.php ENDPATH**/ ?>