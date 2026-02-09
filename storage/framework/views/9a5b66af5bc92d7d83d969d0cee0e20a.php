<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Modifier la Réclamation #<?php echo e($reclamation->id); ?></h1>
        <div>
            <a href="<?php echo e(route('client-reclamations.show', $reclamation)); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour aux détails
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="<?php echo e(route('client-reclamations.update', $reclamation)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informations de base</h2>
                </div>

                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client <span class="text-red-600">*</span></label>
                    <select name="client_id" id="client_id" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un client</option>
                        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($client->id); ?>" <?php echo e(old('client_id', $reclamation->client_id) == $client->id ? 'selected' : ''); ?>>
                                <?php echo e($client->nom_raison_sociale); ?> (<?php echo e($client->code_client); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="type_reclamation" class="block text-sm font-medium text-gray-700 mb-1">Type de réclamation <span class="text-red-600">*</span></label>
                    <select name="type_reclamation" id="type_reclamation" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un type</option>
                        <option value="qualite_produit" <?php echo e(old('type_reclamation', $reclamation->type_reclamation) == 'qualite_produit' ? 'selected' : ''); ?>>Qualité produit</option>
                        <option value="service_client" <?php echo e(old('type_reclamation', $reclamation->type_reclamation) == 'service_client' ? 'selected' : ''); ?>>Service client</option>
                        <option value="livraison" <?php echo e(old('type_reclamation', $reclamation->type_reclamation) == 'livraison' ? 'selected' : ''); ?>>Livraison</option>
                        <option value="facturation" <?php echo e(old('type_reclamation', $reclamation->type_reclamation) == 'facturation' ? 'selected' : ''); ?>>Facturation</option>
                        <option value="autre" <?php echo e(old('type_reclamation', $reclamation->type_reclamation) == 'autre' ? 'selected' : ''); ?>>Autre</option>
                    </select>
                    <?php $__errorArgs = ['type_reclamation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-600">*</span></label>
                    <textarea name="description" id="description" rows="4" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"><?php echo e(old('description', $reclamation->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Traitement -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 mt-4">Traitement</h2>
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut <span class="text-red-600">*</span></label>
                    <select name="statut" id="statut" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="ouverte" <?php echo e(old('statut', $reclamation->statut) == 'ouverte' ? 'selected' : ''); ?>>Ouverte</option>
                        <option value="en_cours" <?php echo e(old('statut', $reclamation->statut) == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                        <option value="resolue" <?php echo e(old('statut', $reclamation->statut) == 'resolue' ? 'selected' : ''); ?>>Résolue</option>
                        <option value="fermee" <?php echo e(old('statut', $reclamation->statut) == 'fermee' ? 'selected' : ''); ?>>Fermée</option>
                    </select>
                    <?php $__errorArgs = ['statut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="agent_id" class="block text-sm font-medium text-gray-700 mb-1">Agent assigné</label>
                    <select name="agent_id" id="agent_id" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un agent</option>
                        <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($agent->id); ?>" <?php echo e(old('agent_id', $reclamation->agent_id) == $agent->id ? 'selected' : ''); ?>>
                                <?php echo e($agent->nom); ?> <?php echo e($agent->prenom); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['agent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="date_resolution" class="block text-sm font-medium text-gray-700 mb-1">Date de résolution</label>
                    <input type="datetime-local" name="date_resolution" id="date_resolution" 
                        value="<?php echo e(old('date_resolution', $reclamation->date_resolution ? date('Y-m-d\TH:i', strtotime($reclamation->date_resolution)) : '')); ?>" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <?php $__errorArgs = ['date_resolution'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="solution" class="block text-sm font-medium text-gray-700 mb-1">Solution</label>
                    <textarea name="solution" id="solution" rows="3" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"><?php echo e(old('solution', $reclamation->solution)); ?></textarea>
                    <?php $__errorArgs = ['solution'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="md:col-span-2">
                    <label for="commentaires" class="block text-sm font-medium text-gray-700 mb-1">Commentaires</label>
                    <textarea name="commentaires" id="commentaires" rows="3" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"><?php echo e(old('commentaires', $reclamation->commentaires)); ?></textarea>
                    <?php $__errorArgs = ['commentaires'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Documents -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 mt-4">Documents</h2>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Documents existants</label>
                    
                    <?php if($reclamation->documents->count() > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
                        <p class="text-gray-500 italic mb-4">Aucun document attaché</p>
                    <?php endif; ?>
                    
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ajouter de nouveaux documents (PDF, JPG, PNG)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="document_preuve" class="block text-sm font-medium text-gray-700 mb-1">Preuve de réclamation</label>
                            <input type="file" name="documents[preuve]" id="document_preuve" accept=".pdf,.jpg,.jpeg,.png" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                        <div>
                            <label for="document_autre" class="block text-sm font-medium text-gray-700 mb-1">Autre document</label>
                            <input type="file" name="documents[autre]" id="document_autre" accept=".pdf,.jpg,.jpeg,.png" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                    </div>
                    <?php $__errorArgs = ['documents'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="<?php echo e(route('client-reclamations.show', $reclamation)); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\client-reclamations\edit.blade.php ENDPATH**/ ?>