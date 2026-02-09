<div <?php echo e($attributes->merge(['class' => 'bg-white shadow rounded-lg'])); ?>>
    <?php if(isset($header)): ?>
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <?php echo e($header); ?>

        </div>
    <?php endif; ?>
    
    <div class="px-4 py-5 sm:p-6">
        <?php echo e($slot); ?>

    </div>
    
    <?php if(isset($footer)): ?>
        <div class="bg-gray-50 px-4 py-4 sm:px-6 rounded-b-lg border-t border-gray-200">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?>
</div><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\components\card.blade.php ENDPATH**/ ?>