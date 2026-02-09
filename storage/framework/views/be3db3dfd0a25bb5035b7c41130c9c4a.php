<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>CONNEXION - TPT-H ERP</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="min-h-screen bg-gray-50 flex flex-col">
    <div class="flex-grow flex items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 fade-in">
            <div class="text-center">
                
                
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                    <div class="mb-6 text-center">
                        <i class="fas fa-lock text-red-600 text-4xl mb-3"></i>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">
                            Connexion
                        </h2>
                        <p class="text-gray-500 text-sm">Entrez vos identifiants</p>
                    </div>

                    <!-- Messages d'erreurs -->
                    <?php if($errors->any()): ?>
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm" role="alert">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-circle mt-1 mr-2 text-red-500"></i>
                                <div>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p><?php echo e($error); ?></p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(session('status')): ?>
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm" role="alert">
                            <div class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                                <div><?php echo e(session('status')); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire -->
                    <form class="space-y-5" action="<?php echo e(route('login')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="space-y-4">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Adresse email
                                </label>
                                <div class="relative">
                                    <input id="email" name="email" type="email" autocomplete="email" required
                                        class="block w-full px-4 py-2.5 text-gray-800 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                                        placeholder="votre@email.com" value="<?php echo e(old('email')); ?>">
                                </div>
                            </div>
                            
                            <!-- Mot de passe -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Mot de passe
                                </label>
                                <div class="relative">
                                    <input id="password" name="password" type="password" autocomplete="current-password" required
                                        class="block w-full px-4 py-2.5 text-gray-800 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                                        placeholder="••••••••">
                                    <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 toggle-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center">
                                <input id="remember" name="remember" type="checkbox"
                                    class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <span class="ml-2 text-gray-700">Se souvenir de moi</span>
                            </label>
                            <a href="<?php echo e(route('password.request')); ?>" class="text-red-600 hover:text-red-800 font-medium transition-colors">
                                Mot de passe oublié ?
                            </a>
                        </div>

                        <!-- Bouton -->
                        <div class="pt-3">
                            <button type="submit"
                                class="w-full py-2.5 px-4 bg-red-600 text-white font-medium text-sm rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-colors">
                                Se connecter
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                        <p class="text-gray-500 text-xs">
                            © <?php echo e(date('Y')); ?> TPT-H ERP. Tous droits réservés.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>

</html>
<?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\auth\login.blade.php ENDPATH**/ ?>