

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-semibold text-gray-900 mb-6">Configuration de l'authentification à deux facteurs</h1>
            
            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    L'authentification à deux facteurs (2FA) ajoute une couche de sécurité supplémentaire à votre compte.
                    Après activation, vous devrez fournir à la fois votre mot de passe et un code généré par une application d'authentification.
                </p>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-blue-800 mb-2">Instructions :</h3>
                    <ol class="list-decimal list-inside text-blue-700 space-y-1">
                        <li>Installez une application d'authentification (comme Google Authenticator, Authy, ou Microsoft Authenticator)</li>
                        <li>Scannez le code QR ci-dessous avec votre application</li>
                        <li>Entrez le code à 6 chiffres généré par votre application</li>
                        <li>Conservez vos codes de récupération en lieu sûr</li>
                    </ol>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Code QR</h3>
                    <div class="bg-gray-100 rounded-lg p-4 flex items-center justify-center">
                        <?php if($qrCodeUrl): ?>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo e(urlencode($qrCodeUrl)); ?>" alt="QR Code 2FA" class="w-48 h-48">
                        <?php else: ?>
                            <p class="text-gray-500">QR Code non disponible</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Code secret</h3>
                    <div class="bg-gray-100 rounded-lg p-4">
                        <p class="font-mono text-sm break-all"><?php echo e($user->twoFactorAuth->secret ?? 'Secret non disponible'); ?></p>
                        <p class="text-xs text-gray-500 mt-2">Utilisez ce code si vous ne pouvez pas scanner le QR Code</p>
                    </div>
                </div>
            </div>
            
            <form action="<?php echo e(route('2fa.enable')); ?>" method="POST" class="mb-6">
                <?php echo csrf_field(); ?>
                
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700">Code de vérification (6 chiffres)</label>
                    <input type="text" name="code" id="code" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Entrez le code à 6 chiffres">
                    <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="flex items-center">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Activer l'authentification à deux facteurs
                    </button>
                    
                    <a href="<?php echo e(route('2fa.recovery')); ?>" class="ml-4 text-sm text-blue-600 hover:text-blue-800">
                        Voir les codes de récupération
                    </a>
                </div>
            </form>
            
            <div class="border-t border-gray-200 pt-4">
                <a href="<?php echo e(route('dashboard')); ?>" class="text-sm text-gray-600 hover:text-gray-800">
                    ← Retour au tableau de bord
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\auth\2fa\setup.blade.php ENDPATH**/ ?>