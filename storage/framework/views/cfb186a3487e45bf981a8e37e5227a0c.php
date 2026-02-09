<?php $__env->startSection('title', 'Détails de l\'Inventaire'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h3 class="text-xl font-semibold">Détails de l'Inventaire #<?php echo e($inventory->reference); ?></h3>
        <div class="space-x-2">
            <a href="<?php echo e(route('stock.inventories.index')); ?>" 
               class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-1"></i> Retour
            </a>
            <button onclick="window.print()" 
                    class="inline-flex items-center px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                <i class="fas fa-print mr-1"></i> Imprimer
            </button>
            <a href="<?php echo e(route('stock.inventories.pdf', $inventory)); ?>" 
               class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
        </div>
    </div>

    <!-- Informations Générales & Complémentaires -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Informations Générales -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h4 class="font-semibold text-gray-700">Informations Générales</h4>
            </div>
            <div class="px-6 py-4">
                <table class="min-w-full text-sm text-gray-700">
                    <tr class="border-b">
                        <th class="text-left w-1/3 py-1">Référence</th>
                        <td><?php echo e($inventory->reference); ?></td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-1">Date</th>
                        <td><?php echo e($inventory->date->format('d/m/Y')); ?></td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-1">Dépôt</th>
                        <td><?php echo e($inventory->warehouse->nom); ?></td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-1">Statut</th>
                        <td>
                            <?php if($inventory->status == 'en_cours'): ?>
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-200 rounded-full">En cours</span>
                            <?php elseif($inventory->status == 'valide'): ?>
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-200 rounded-full">Validé</span>
                            <?php else: ?>
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-red-700 bg-red-200 rounded-full">Annulé</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left py-1">Notes</th>
                        <td><?php echo e($inventory->notes ?? '-'); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Informations Complémentaires -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h4 class="font-semibold text-gray-700">Informations Complémentaires</h4>
            </div>
            <div class="px-6 py-4">
                <table class="min-w-full text-sm text-gray-700">
                    <tr class="border-b">
                        <th class="text-left w-1/3 py-1">Créé par</th>
                        <td><?php echo e($inventory->createdBy->name); ?></td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-1">Date de création</th>
                        <td><?php echo e($inventory->created_at->format('d/m/Y H:i')); ?></td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-1">Validé par</th>
                        <td><?php echo e($inventory->validatedBy?->name ?? 'Non validé'); ?></td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-left py-1">Date de validation</th>
                        <td><?php echo e($inventory->validated_at ? $inventory->validated_at->format('d/m/Y H:i') : 'Non validé'); ?></td>
                    </tr>
                    <tr>
                        <th class="text-left py-1">Nombre de produits</th>
                        <td><?php echo e($inventory->items->count()); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Détails de l'inventaire -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h4 class="font-semibold text-gray-700">Détails de l'Inventaire</h4>
            <?php if($inventory->status == 'en_cours'): ?>
                <a href="<?php echo e(route('stock.inventories.edit', $inventory)); ?>" 
                   class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-edit mr-1"></i> Modifier
                </a>
            <?php endif; ?>
        </div>
        <div class="px-6 py-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left">Produit</th>
                        <th class="px-3 py-2 text-left">Référence</th>
                        <th class="px-3 py-2 text-right">Stock Théorique</th>
                        <th class="px-3 py-2 text-right">Stock Réel</th>
                        <th class="px-3 py-2 text-right">Différence</th>
                        <th class="px-3 py-2 text-right">Valeur Différence</th>
                        <th class="px-3 py-2 text-left">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php
                        $totalDifference = 0;
                        $totalDifferenceValue = 0;
                    ?>
                    <?php $__currentLoopData = $inventory->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $differenceValue = $item->difference !== null ? $item->difference * $item->product->prix_achat : 0;
                            $totalDifference += $item->difference ?? 0;
                            $totalDifferenceValue += $differenceValue;
                        ?>
                        <tr>
                            <td class="px-3 py-2"><?php echo e($item->product->name); ?></td>
                            <td class="px-3 py-2"><?php echo e($item->product->reference); ?></td>
                            <td class="px-3 py-2 text-right"><?php echo e(number_format($item->theoretical_quantity, 2)); ?></td>
                            <td class="px-3 py-2 text-right"><?php echo e($item->actual_quantity !== null ? number_format($item->actual_quantity, 2) : '-'); ?></td>
                            <td class="px-3 py-2 text-right">
                                <?php if($item->difference !== null): ?>
                                    <span class="<?php echo e($item->difference < 0 ? 'text-red-600' : ($item->difference > 0 ? 'text-green-600' : '')); ?>">
                                        <?php echo e(number_format($item->difference, 2)); ?>

                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-2 text-right">
                                <?php if($item->difference !== null): ?>
                                    <span class="<?php echo e($differenceValue < 0 ? 'text-red-600' : ($differenceValue > 0 ? 'text-green-600' : '')); ?>">
                                        <?php echo e(number_format($differenceValue, 2)); ?>

                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-2"><?php echo e($item->notes ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="bg-gray-100 font-semibold">
                    <tr>
                        <th colspan="4" class="text-right px-3 py-2">Total des Différences:</th>
                        <th class="text-right px-3 py-2 <?php echo e($totalDifference < 0 ? 'text-red-600' : ($totalDifference > 0 ? 'text-green-600' : '')); ?>">
                            <?php echo e(number_format($totalDifference, 2)); ?>

                        </th>
                        <th class="text-right px-3 py-2 <?php echo e($totalDifferenceValue < 0 ? 'text-red-600' : ($totalDifferenceValue > 0 ? 'text-green-600' : '')); ?>">
                            <?php echo e(number_format($totalDifferenceValue, 2)); ?>

                        </th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <?php if($inventory->status == 'en_cours'): ?>
        <div class="text-center mt-4">
            <form action="<?php echo e(route('stock.inventories.validate', $inventory)); ?>" method="POST" class="inline-block">
                <?php echo csrf_field(); ?>
                <!-- Suppression de <?php echo method_field('PATCH'); ?> car la route accepte POST -->
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                        onclick="return confirm('Êtes-vous sûr de vouloir valider cet inventaire? Cette action est irréversible.')">
                    <i class="fas fa-check mr- 1"></i> Valider l'Inventaire
                </button>
            </form>
        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\inventories\show.blade.php ENDPATH**/ ?>