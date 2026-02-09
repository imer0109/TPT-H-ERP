<!DOCTYPE html>
<html>
<head>
    <title>Test Blade Compilation</title>
</head>
<body>
    <h1>Test de compilation Blade</h1>
    <p>Si vous voyez ce texte, Blade fonctionne correctement.</p>
    <p>Heure actuelle : <?php echo e(now()->format('Y-m-d H:i:s')); ?></p>
    <p>Variable test : <?php echo e($test ?? 'Variable non définie'); ?></p>
    
    <?php if(isset($test)): ?>
        <p>La variable test est définie : <?php echo e($test); ?></p>
    <?php else: ?>
        <p>La variable test n'est pas définie</p>
    <?php endif; ?>
    
    <ul>
        <?php for($i = 1; $i <= 3; $i++): ?>
            <li>Élément <?php echo e($i); ?></li>
        <?php endfor; ?>
    </ul>
</body>
</html><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/test-blade.blade.php ENDPATH**/ ?>