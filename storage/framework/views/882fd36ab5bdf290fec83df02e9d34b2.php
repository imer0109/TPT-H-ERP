<!DOCTYPE html>
<html>
<head>
    <title>Test Final</title>
</head>
<body>
    <h1>TEST FINAL - <?php echo e(date('Y-m-d H:i:s')); ?></h1>
    <p>Si vous voyez ce message, Blade fonctionne correctement.</p>
    <p>Contenu de la section 'content':</p>
    <div style="border: 1px solid #ccc; padding: 10px; margin: 10px;">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</body>
</html><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\test_final.blade.php ENDPATH**/ ?>