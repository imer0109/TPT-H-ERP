<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Portail Fournisseur - TPT-H ERP'); ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Custom styles -->
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        @media (min-width: 768px) {
            .sidebar.collapsed {
                transform: none;
                width: 64px;
            }
            .sidebar.collapsed .sidebar-text {
                display: none;
            }
            .sidebar.collapsed .sidebar-icon {
                margin: auto;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        <div class="sidebar bg-gray-800 text-white w-64 flex-shrink-0 flex flex-col" 
             :class="{ 'collapsed': !sidebarOpen }">
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="sidebar-icon bg-blue-600 rounded-lg p-2 mr-3">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="sidebar-text">
                            <h1 class="text-xl font-bold">TPT-H ERP</h1>
                            <p class="text-xs text-gray-400">Portail Fournisseur</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                <nav class="mt-4">
                    <a href="<?php echo e(route('supplier.portal.index')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.index') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-tachometer-alt sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Tableau de bord</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.profile')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.profile') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-user sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Profil</span>
                    </a>
                    
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider sidebar-text">
                        Opérations
                    </div>
                    
                    <a href="<?php echo e(route('supplier.portal.orders')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.orders*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-file-invoice sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Commandes</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.deliveries')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.deliveries*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-truck-loading sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Livraisons</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.invoices')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.invoices*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-file-invoice-dollar sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Factures</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.payments')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.payments*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-money-bill-wave sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Paiements</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.contracts')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.contracts*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-file-contract sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Contrats</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.issues')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.issues*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                        <i class="fas fa-exclamation-triangle sidebar-icon w-6 text-center"></i>
                        <span class="sidebar-text ml-3">Réclamations</span>
                    </a>
                    
                    <a href="<?php echo e(route('supplier.portal.integrations.index')); ?>" 
                       class="flex items-center px-4 py-3 text-sm font-medium <?php echo e(request()->routeIs('supplier.portal.integrations*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
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
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-700 mr-3">
                            <i class="fas fa-bars"></i>
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
    
    <!-- Scripts -->
    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const toggleButton = document.querySelector('[x-on\\:click="sidebarOpen = !sidebarOpen"]');
            
            if (window.innerWidth < 768) {
                sidebar.classList.add('collapsed');
            }
            
            window.addEventListener('resize', function() {
                if (window.innerWidth < 768) {
                    sidebar.classList.add('collapsed');
                } else {
                    sidebar.classList.remove('collapsed');
                }
            });
        });
    </script>
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\portal\layout.blade.php ENDPATH**/ ?>