

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Bon de Commande <?php echo e($order->code); ?></h1>
                <div class="flex items-center space-x-4 mt-2">
                    <?php
                        $statusColors = [
                            'Brouillon' => 'bg-gray-100 text-gray-800',
                            'En attente' => 'bg-yellow-100 text-yellow-800',
                            'Envoyé' => 'bg-blue-100 text-blue-800',
                            'Confirmé' => 'bg-green-100 text-green-800',
                            'Livré' => 'bg-purple-100 text-purple-800',
                            'Clôturé' => 'bg-gray-100 text-gray-800',
                            'Annulé' => 'bg-red-100 text-red-800'
                        ];
                    ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($statusColors[$order->statut] ?? 'bg-gray-100 text-gray-800'); ?>">
                        <?php echo e($order->statut); ?>

                    </span>
                    <span class="text-sm text-gray-600">Créé le <?php echo e($order->created_at->format('d/m/Y à H:i')); ?></span>
                    <?php if($order->purchaseRequest): ?>
                        <span class="text-sm text-blue-600">
                            <i class="fas fa-link mr-1"></i>DA: <?php echo e($order->purchaseRequest->code); ?>

                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="<?php echo e(route('purchases.orders.index')); ?>" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
                <?php if(in_array($order->statut, ['Brouillon', 'En attente'])): ?>
                    <a href="<?php echo e(route('purchases.orders.edit', $order)); ?>" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('purchases.orders.pdf', $order)); ?>" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-file-pdf mr-2"></i>PDF
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails commande -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Détails de la commande</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nature d'achat</p>
                        <p class="text-base"><?php echo e($order->nature_achat); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de commande</p>
                        <p class="text-base"><?php echo e($order->date_commande->format('d/m/Y')); ?></p>
                    </div>
                    <?php if($order->delai_contractuel): ?>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Délai contractuel</p>
                        <p class="text-base"><?php echo e($order->delai_contractuel->format('d/m/Y')); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($order->conditions_paiement): ?>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Conditions de paiement</p>
                        <p class="text-base"><?php echo e($order->conditions_paiement); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($order->adresse_livraison): ?>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500">Adresse de livraison</p>
                        <p class="text-base"><?php echo e($order->adresse_livraison); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($order->notes): ?>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500">Notes</p>
                        <p class="text-base"><?php echo e($order->notes); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Articles -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Articles commandés</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Désignation</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qté</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Unité</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">P.U.</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($item->designation); ?></div>
                                        <?php if($item->product): ?>
                                            <div class="text-xs text-gray-500">Réf: <?php echo e($item->product->reference ?? $item->product->name); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-900"><?php echo e($item->quantite); ?></td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-900"><?php echo e($item->unite); ?></td>
                                    <td class="px-4 py-3 text-right text-sm text-gray-900">
                                        <?php echo e(number_format($item->prix_unitaire, 0, ',', ' ')); ?> <?php echo e($order->devise); ?>

                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                        <?php echo e(number_format($item->montant_total, 0, ',', ' ')); ?> <?php echo e($order->devise); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-900">Total HT</td>
                                <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                    <?php echo e(number_format($order->montant_ht, 0, ',', ' ')); ?> <?php echo e($order->devise); ?>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-900">TVA</td>
                                <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                    <?php echo e(number_format($order->montant_tva, 0, ',', ' ')); ?> <?php echo e($order->devise); ?>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-lg font-bold text-gray-900">Total TTC</td>
                                <td class="px-4 py-3 text-right text-lg font-bold text-red-600">
                                    <?php echo e(number_format($order->montant_ttc, 0, ',', ' ')); ?> <?php echo e($order->devise); ?>

                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Fournisseur -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Fournisseur</h3>
                <?php if($order->fournisseur): ?>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Raison sociale</p>
                            <p class="text-base font-medium"><?php echo e($order->fournisseur->raison_sociale); ?></p>
                        </div>
                        <?php if($order->fournisseur->contact_principal): ?>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Contact</p>
                            <p class="text-base"><?php echo e($order->fournisseur->contact_principal); ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if($order->fournisseur->telephone): ?>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Téléphone</p>
                            <p class="text-base">
                                <a href="tel:<?php echo e($order->fournisseur->telephone); ?>" class="text-blue-600 hover:text-blue-800">
                                    <?php echo e($order->fournisseur->telephone); ?>

                                </a>
                            </p>
                        </div>
                        <?php endif; ?>
                        <?php if($order->fournisseur->email): ?>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <p class="text-base">
                                <a href="mailto:<?php echo e($order->fournisseur->email); ?>" class="text-blue-600 hover:text-blue-800">
                                    <?php echo e($order->fournisseur->email); ?>

                                </a>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Agence -->
            <?php if($order->agency): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Agence</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nom</p>
                        <p class="text-base font-medium"><?php echo e($order->agency->nom); ?></p>
                    </div>
                    <?php if($order->agency->adresse): ?>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Adresse</p>
                        <p class="text-base"><?php echo e($order->agency->adresse); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Créé par -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Créé par</p>
                        <p class="text-base"><?php echo e($order->createdBy->name ?? 'Utilisateur supprimé'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de création</p>
                        <p class="text-base"><?php echo e($order->created_at->format('d/m/Y à H:i')); ?></p>
                    </div>
                    <?php if($order->updated_at != $order->created_at): ?>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Dernière modification</p>
                        <p class="text-base"><?php echo e($order->updated_at->format('d/m/Y à H:i')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                <div class="space-y-3">
                    <?php if($order->statut === 'Confirmé'): ?>
                        <a href="<?php echo e(route('purchases.orders.create-delivery', $order)); ?>" 
                           class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition text-center block">
                            <i class="fas fa-truck mr-2"></i>Créer une livraison
                        </a>
                        <a href="<?php echo e(route('purchases.orders.create-payment', $order)); ?>" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-center block">
                            <i class="fas fa-credit-card mr-2"></i>Créer un paiement
                        </a>
                    <?php endif; ?>

                    <?php if(in_array($order->statut, ['Brouillon', 'En attente', 'Envoyé'])): ?>
                        <form method="POST" action="<?php echo e(route('purchases.orders.update-status', $order)); ?>" class="space-y-2">
                            <?php echo csrf_field(); ?>
                            <select name="statut" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <?php $__currentLoopData = ['Brouillon', 'En attente', 'Envoyé', 'Confirmé', 'Annulé']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statut): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($statut); ?>" <?php echo e($order->statut == $statut ? 'selected' : ''); ?>>
                                        <?php echo e($statut); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-save mr-2"></i>Changer le statut
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\purchases\orders\show.blade.php ENDPATH**/ ?>