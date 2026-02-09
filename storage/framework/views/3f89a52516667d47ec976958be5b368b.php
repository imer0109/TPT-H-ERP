<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <?php echo e($header); ?>

            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php echo e($body); ?>

            </tbody>
        </table>
    </div>
    
    <?php if(isset($pagination)): ?>
    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
        <?php echo e($pagination); ?>

    </div>
    <?php endif; ?>
</div><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\components\table.blade.php ENDPATH**/ ?>