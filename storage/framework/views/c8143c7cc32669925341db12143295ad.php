<div class="bg-white rounded-lg shadow p-6">
    <form action="<?php echo e($action); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-<?php echo e($columns ?? 4); ?> gap-4">
        <?php echo e($slot); ?>

        
        <div class="<?php echo e(isset($fullWidth) ? 'md:col-span-full' : 'md:col-span-' . ($columns ?? 4)); ?> flex justify-end gap-2 mt-2">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Filtrer
            </button>
            <a href="<?php echo e($action); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Réinitialiser
            </a>
        </div>
    </form>
</div><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\components\crud-filters.blade.php ENDPATH**/ ?>