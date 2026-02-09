

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800"> Produits</h1>
        <a href="<?php echo e(route('stock.products.create')); ?>" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-md transition">
             Nouveau produit
        </a>
    </div>

    <!-- Search -->
    <form method="get" class="mb-6">
        <div class="flex gap-2">
            <input name="q" value="<?php echo e(request('q')); ?>" 
                   placeholder="Rechercher un produit..." 
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
            <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg shadow-sm transition">
                 Filtrer
            </button>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <table class="table-auto w-full text-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Nom</th>
                    <th class="px-4 py-3 text-left">Quantité</th>
                    <th class="px-4 py-3 text-left">Prix unitaire</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-t odd:bg-gray-50 hover:bg-gray-100 transition">
                        <td class="px-4 py-3 font-medium text-gray-800"><?php echo e($product->name); ?></td>
                        <td class="px-4 py-3"><?php echo e($product->quantite); ?></td>
                        <td class="px-4 py-3"><?php echo e(number_format($product->prix_unitaire, 0, ',', ' ')); ?> FCFA </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="<?php echo e(route('stock.products.edit', $product)); ?>" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg shadow-sm text-xs">
                                 Modifier
                            </a>
                            <form action="<?php echo e(route('stock.products.destroy', $product)); ?>" method="post" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button onclick="return confirm('Supprimer ce produit ?')" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow-sm text-xs">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-gray-500">Aucun produit trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="p-4 border-t">
            <?php echo e($products->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\stock\products\index.blade.php ENDPATH**/ ?>