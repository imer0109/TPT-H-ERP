

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Demande d'Achat <?php echo e($request->code); ?></h1>
                <p class="text-gray-600 mt-1"><?php echo e($request->designation); ?></p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('purchases.requests.index')); ?>" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
                <?php if($request->canBeEdited()): ?>
                    <a href="<?php echo e(route('purchases.requests.edit', $request)); ?>" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                <?php endif; ?>
                <?php if($request->canBeConverted()): ?>
                    <form method="POST" action="<?php echo e(route('purchases.requests.convert-to-boc', $request)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                            <i class="fas fa-exchange-alt mr-2"></i>Convertir en BOC
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails de la demande -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Détails de la demande</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Code</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($request->code); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nature de l'achat</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php echo e($request->nature_achat == 'Bien' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'); ?>">
                                <?php echo e($request->nature_achat); ?>

                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Société</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($request->company->name); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Agence</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($request->agency->name ?? 'Non spécifiée'); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Demandeur</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($request->requestedBy->nom); ?> <?php echo e($request->requestedBy->prenom); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date de demande</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($request->date_demande->format('d/m/Y')); ?></p>
                    </div>
                    <?php if($request->date_echeance_souhaitee): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Échéance souhaitée</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($request->date_echeance_souhaitee->format('d/m/Y')); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($request->fournisseurSuggere): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Fournisseur suggéré</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($request->fournisseurSuggere->nom); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500">Justification</label>
                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line"><?php echo e($request->justification); ?></p>
                </div>

                <?php if($request->notes): ?>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-500">Notes</label>
                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line"><?php echo e($request->notes); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Articles/Services -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Articles / Services</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Désignation</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $request->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($item->designation); ?></div>
                                        <?php if($item->description): ?>
                                            <div class="text-sm text-gray-500"><?php echo e($item->description); ?></div>
                                        <?php endif; ?>
                                        <?php if($item->product): ?>
                                            <div class="text-xs text-blue-600">Produit: <?php echo e($item->product->libelle); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo e($item->quantite); ?> <?php echo e($item->unite); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo e(number_format($item->prix_unitaire_estime, 0, ',', ' ')); ?> FCFA</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e(number_format($item->montant_total_estime, 0, ',', ' ')); ?> FCFA</div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Total estimé</td>
                                <td class="px-6 py-3 text-sm font-bold text-gray-900"><?php echo e(number_format($request->prix_estime_total, 0, ',', ' ')); ?> FCFA</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Fichiers joints -->
            <?php if($request->files->count() > 0): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Fichiers joints</h2>
                <div class="space-y-3">
                    <?php $__currentLoopData = $request->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-file text-gray-400 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($file->filename); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($file->description ?? 'Aucune description'); ?> • <?php echo e(human_filesize($file->size)); ?></p>
                                </div>
                            </div>
                            <a href="<?php echo e(Storage::url($file->path)); ?>" target="_blank" 
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Bons de commande liés -->
            <?php if($request->supplierOrders->count() > 0): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Bons de commande liés</h2>
                <div class="space-y-3">
                    <?php $__currentLoopData = $request->supplierOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900"><?php echo e($order->code); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($order->fournisseur->nom); ?> • <?php echo e(number_format($order->montant_ttc, 0, ',', ' ')); ?> FCFA</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php switch($order->statut):
                                        case ('Brouillon'): ?> bg-gray-100 text-gray-800 <?php break; ?>
                                        <?php case ('En attente'): ?> bg-yellow-100 text-yellow-800 <?php break; ?>
                                        <?php case ('Envoyé'): ?> bg-blue-100 text-blue-800 <?php break; ?>
                                        <?php case ('Confirmé'): ?> bg-green-100 text-green-800 <?php break; ?>
                                        <?php case ('Livré'): ?> bg-purple-100 text-purple-800 <?php break; ?>
                                        <?php case ('Clôturé'): ?> bg-gray-100 text-gray-800 <?php break; ?>
                                        <?php case ('Annulé'): ?> bg-red-100 text-red-800 <?php break; ?>
                                        <?php default: ?> bg-gray-100 text-gray-800
                                    <?php endswitch; ?>">
                                    <?php echo e($order->statut); ?>

                                </span>
                                <a href="<?php echo e(route('purchases.orders.show', $order)); ?>" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statut et actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Statut et actions</h2>
                
                <div class="mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        <?php switch($request->statut):
                            case ('Brouillon'): ?> bg-gray-100 text-gray-800 <?php break; ?>
                            <?php case ('En attente'): ?> bg-yellow-100 text-yellow-800 <?php break; ?>
                            <?php case ('Validée'): ?> bg-green-100 text-green-800 <?php break; ?>
                            <?php case ('Refusée'): ?> bg-red-100 text-red-800 <?php break; ?>
                            <?php case ('Convertie en BOC'): ?> bg-blue-100 text-blue-800 <?php break; ?>
                            <?php case ('Annulée'): ?> bg-gray-100 text-gray-800 <?php break; ?>
                            <?php default: ?> bg-gray-100 text-gray-800
                        <?php endswitch; ?>">
                        <?php echo e($request->statut); ?>

                    </span>
                </div>

                <?php if($request->canBeValidated()): ?>
                    <div class="space-y-3">
                        <form method="POST" action="<?php echo e(route('purchases.requests.validate', $request)); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="action" value="approve">
                            <textarea name="commentaires" placeholder="Commentaires (optionnel)" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3"></textarea>
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-check mr-2"></i>Approuver
                            </button>
                        </form>
                        <form method="POST" action="<?php echo e(route('purchases.requests.validate', $request)); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="action" value="reject">
                            <textarea name="commentaires" placeholder="Motif du refus" required
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3"></textarea>
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-times mr-2"></i>Refuser
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if($request->validated_by): ?>
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-600">
                            Validé par <?php echo e($request->validatedBy->nom); ?> <?php echo e($request->validatedBy->prenom); ?>

                            le <?php echo e($request->validated_at->format('d/m/Y à H:i')); ?>

                        </p>
                        <?php if($request->validation_comments): ?>
                            <p class="text-sm text-gray-800 mt-2"><?php echo e($request->validation_comments); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Historique des validations -->
            <?php if($request->validations->count() > 0 || ($request->validationRequest && $request->validationRequest->validationSteps->count() > 0)): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Historique des validations</h2>
                
                <!-- Advanced validation workflow steps -->
                <?php if($request->validationRequest && $request->validationRequest->validationSteps->count() > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $request->validationRequest->validationSteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs
                                        <?php switch($step->action):
                                            case ('approved'): ?> bg-green-100 text-green-800 <?php break; ?>
                                            <?php case ('rejected'): ?> bg-red-100 text-red-800 <?php break; ?>
                                            <?php default: ?> bg-gray-100 text-gray-800
                                        <?php endswitch; ?>">
                                        <?php switch($step->action):
                                            case ('approved'): ?> <i class="fas fa-check"></i> <?php break; ?>
                                            <?php case ('rejected'): ?> <i class="fas fa-times"></i> <?php break; ?>
                                            <?php default: ?> <i class="fas fa-clock"></i>
                                        <?php endswitch; ?>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        <?php echo e($step->validator->nom); ?> <?php echo e($step->validator->prenom); ?>

                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?php echo e($step->getActionDisplayName()); ?>

                                        <?php if($step->validated_at): ?>
                                            • <?php echo e($step->validated_at->format('d/m/Y à H:i')); ?>

                                        <?php endif; ?>
                                    </p>
                                    <?php if($step->notes): ?>
                                        <p class="text-xs text-gray-700 mt-1"><?php echo e($step->notes); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <!-- Original simple validation history -->
                    <div class="space-y-3">
                        <?php $__currentLoopData = $request->validations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $validation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs
                                        <?php switch($validation->statut):
                                            case ('En attente'): ?> bg-yellow-100 text-yellow-800 <?php break; ?>
                                            <?php case ('Approuvée'): ?> bg-green-100 text-green-800 <?php break; ?>
                                            <?php case ('Rejetée'): ?> bg-red-100 text-red-800 <?php break; ?>
                                            <?php default: ?> bg-gray-100 text-gray-800
                                        <?php endswitch; ?>">
                                        <?php switch($validation->statut):
                                            case ('En attente'): ?> <i class="fas fa-clock"></i> <?php break; ?>
                                            <?php case ('Approuvée'): ?> <i class="fas fa-check"></i> <?php break; ?>
                                            <?php case ('Rejetée'): ?> <i class="fas fa-times"></i> <?php break; ?>
                                        <?php endswitch; ?>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($validation->validatedBy->nom); ?> <?php echo e($validation->validatedBy->prenom); ?></p>
                                    <p class="text-xs text-gray-500">
                                        <?php echo e($validation->statut); ?>

                                        <?php if($validation->validated_at): ?>
                                            • <?php echo e($validation->validated_at->format('d/m/Y à H:i')); ?>

                                        <?php endif; ?>
                                    </p>
                                    <?php if($validation->commentaires): ?>
                                        <p class="text-xs text-gray-700 mt-1"><?php echo e($validation->commentaires); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Ajouter un fichier -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ajouter un fichier</h2>
                <form method="POST" action="<?php echo e(route('purchases.requests.upload-file', $request)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-3">
                        <div>
                            <input type="file" name="file" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <input type="text" name="description" placeholder="Description du fichier"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                            <i class="fas fa-upload mr-2"></i>Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\purchases\requests\show.blade.php ENDPATH**/ ?>