

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-semibold text-gray-900 mb-6">Codes de récupération 2FA</h1>
            
            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    Ces codes de récupération peuvent être utilisés pour accéder à votre compte si vous ne pouvez pas générer un code d'authentification à deux facteurs.
                </p>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-yellow-800 mb-2">Important :</h3>
                    <ul class="list-disc list-inside text-yellow-700 space-y-1">
                        <li>Conservez ces codes en lieu sûr</li>
                        <li>Chaque code ne peut être utilisé qu'une seule fois</li>
                        <li>Vous pouvez régénérer ces codes à tout moment</li>
                    </ul>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <?php $__currentLoopData = $recoveryCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-gray-100 rounded-lg p-3 text-center font-mono text-sm">
                    <?php echo e($code); ?>

                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="flex flex-wrap gap-4 mb-6">
                <form action="<?php echo e(route('2fa.regenerate-recovery')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Régénérer les codes
                    </button>
                </form>
                
                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Imprimer les codes
                </button>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                <a href="<?php echo e(route('2fa.setup')); ?>" class="text-sm text-gray-600 hover:text-gray-800">
                    ← Retour à la configuration 2FA
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\auth\2fa\recovery.blade.php ENDPATH**/ ?>