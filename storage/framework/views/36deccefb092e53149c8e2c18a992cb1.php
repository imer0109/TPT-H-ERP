<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Portail Fournisseur - TPT-H ERP'); ?></title>
    
    <!-- Tailwind + JS build -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <!-- Fallback Tailwind (si le build Vite n'est pas chargé) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
 
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100" @keydown.window.escape="sidebarOpen = false" x-data="{ sidebarOpen: false }">
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-40 md:hidden" @click="sidebarOpen = false" x-cloak></div>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-[85vw] sm:w-64 bg-gray-800 text-white transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-auto flex flex-col h-full shadow-xl">
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="sidebar-icon bg-primary-600 rounded-lg p-2 mr-3">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="sidebar-text">
                            <h1 class="text-xl font-bold">TPT-H ERP</h1>
                            <p class="text-xs text-gray-400">Portail Fournisseur</p>
                        </div>
                    </div>
                    <button class="md:hidden text-gray-400 hover:text-white" @click="sidebarOpen = false" aria-label="Fermer le menu">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                <nav class="mt-4">
                    <a href="<?php echo e(route('supplier.portal.index')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.index') ? 'bg-primary-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-tachometer-alt sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Tableau de bord</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.profile')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.profile') ? 'bg-primary-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-user sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Profil</span>
                    </a>
                    
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider sidebar-text">
                        Opérations
                    </div>
                    
                    <a href="<?php echo e(route('supplier.portal.orders')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.orders*') ? 'bg-primary-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-file-invoice sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Commandes</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.deliveries')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.deliveries*') ? 'bg-primary-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-truck-loading sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Livraisons</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.invoices')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.invoices*') ? 'bg-primary-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-file-invoice-dollar sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Factures</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.payments')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.payments*') ? 'bg-primary-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-money-bill-wave sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Paiements</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.contracts')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.contracts*') ? 'bg-primary-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-file-contract sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Contrats</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.issues')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.issues*') ? 'bg-primary-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-exclamation-triangle sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Réclamations</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.integrations.index')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.integrations*') ? 'bg-primary-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-plug sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Intégrations</span>
                    </a>
                </nav>
            </div>
            
            <div class="p-4 border-t border-gray-700">
                <a href="<?php echo e(route('logout')); ?>" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="flex items-center text-gray-300 hover:text-white">
                    <i class="fas fa-sign-out-alt sidebar-icon w-6 text-center"></i>
                    <span class="sidebar-text ml-3">Déconnexion</span>
                </a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
        </div>
        
        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="md:hidden text-gray-500 hover:text-gray-700 mr-3" aria-label="Ouvrir le menu">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h2 class="text-lg font-semibold text-gray-800"><?php echo $__env->yieldContent('header', 'Portail Fournisseur'); ?></h2>
                    </div>
                    <div class="flex items-center">
                        <div class="mr-4 text-sm text-gray-600">
                            Bienvenue, <span class="font-medium"><?php echo e(Auth::user()->fournisseur->raison_sociale ?? Auth::user()->name); ?></span>
                        </div>
                        <div class="relative">
                            <button class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-bell"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-4 bg-gray-100">
                <?php if(session('success')): ?>
                    <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium"><?php echo e(session('success')); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if(session('error')): ?>
                    <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-800">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium"><?php echo e(session('error')); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if($errors->any()): ?>
                    <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-800">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">Veuillez corriger les erreurs suivantes :</p>
                                <ul class="mt-2 list-disc pl-5 space-y-1">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="text-sm"><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/fournisseurs/portal/layout.blade.php ENDPATH**/ ?>