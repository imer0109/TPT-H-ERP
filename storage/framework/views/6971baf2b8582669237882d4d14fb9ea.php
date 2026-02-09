<div class="chart-node">
    <div class="node-content">
        <div class="node-title"><?php echo e($position->title); ?></div>
        <?php if($position->department): ?>
            <div class="node-department"><?php echo e($position->department->name); ?></div>
        <?php endif; ?>
        <div class="node-employees">
            <?php echo e($position->employees->count()); ?> employé<?php echo e($position->employees->count() > 1 ? 's' : ''); ?>

        </div>
        <div class="node-actions">
            <a href="<?php echo e(route('hr.positions.show', $position)); ?>">Voir</a>
            <a href="<?php echo e(route('hr.positions.edit', $position)); ?>">Modifier</a>
        </div>
    </div>
    
    <?php if($position->childPositions->count() > 0): ?>
        <div class="node-children">
            <?php $__currentLoopData = $position->childPositions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('positions._chart-node', ['position' => $child], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\positions\_chart-node.blade.php ENDPATH**/ ?>