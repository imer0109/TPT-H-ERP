

<?php $__env->startSection('title', 'Test avec Layout Simplifié'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Page de test avec layout simplifié</h2>
    <p>Date: <?php echo e(date('Y-m-d H:i:s')); ?></p>
    <p>Cette page utilise un layout simplifié sans Alpine.js ni vérifications d'authentification complexes.</p>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.test', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\test_layout.blade.php ENDPATH**/ ?>