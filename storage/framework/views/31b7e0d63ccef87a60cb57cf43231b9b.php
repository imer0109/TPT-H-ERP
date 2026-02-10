<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>TPT-H ERP | Administration</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts & Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        /* Custom Scrollbar for Sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 5px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>

<body class="h-full overflow-hidden" x-data="{ sidebarOpen: false }">
    
    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden"
         @click="sidebarOpen = false" x-cloak></div>

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col shadow-xl border-r border-slate-800">
            
            <!-- Logo -->
            
            <div class="flex items-center justify-center h-16 bg-slate-950 px-6 border-b border-slate-800 flex-shrink-0">
                <a href="/" class="flex items-center space-x-3 group">
                    <img src="<?php echo e(asset('images/logo-tpt.png')); ?>" alt="Logo TPT" class="h-10 w-auto">TPT-H ERP
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1 sidebar-scroll">
                
                <!-- Dashboard -->
                <a href="/" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 <?php echo e(request()->is('/') || request()->is('dashboard*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('/') || request()->is('dashboard*') ? 'text-white' : 'text-slate-500 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Tableau de bord
                </a>

                <div class="pt-6 pb-2">
                    <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Administration</p>
                </div>

                <!-- Sociétés -->
                <?php if(auth()->user() && auth()->user()->canAccessModule('companies')): ?>
                <div x-data="{ open: <?php echo e(request()->is('companies*') || request()->is('societes*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo e(request()->is('companies*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                        <div class="flex items-center">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('companies*') ? 'text-primary-500' : 'text-slate-500'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Sociétés
                        </div>
                        <svg :class="open ? 'rotate-90' : ''" class="ml-2 h-4 w-4 text-slate-500 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="space-y-1 mt-1 px-2">
                        <a href="<?php echo e(route('companies.index')); ?>" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->routeIs('companies.index') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Liste des sociétés</a>
                        <a href="<?php echo e(route('companies.create')); ?>" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->routeIs('companies.create') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Nouvelle société</a>
                        <a href="<?php echo e(route('audit-trails.index')); ?>?entity_type=company" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->routeIs('audit-trails.index') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Audit & Historique</a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Agences -->
                <?php if(auth()->user() && auth()->user()->canAccessModule('agencies')): ?>
                <div x-data="{ open: <?php echo e(request()->is('agencies*') || request()->is('agences*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo e(request()->is('agencies*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                        <div class="flex items-center">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('agencies*') ? 'text-primary-500' : 'text-slate-500'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                            Agences
                        </div>
                        <svg :class="open ? 'rotate-90' : ''" class="ml-2 h-4 w-4 text-slate-500 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="space-y-1 mt-1 px-2">
                        <a href="<?php echo e(route('agencies.index')); ?>" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->routeIs('agencies.index') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Liste des agences</a>
                        <a href="<?php echo e(route('agencies.create')); ?>" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->routeIs('agencies.create') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Nouvelle agence</a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Utilisateurs -->
                <?php if(auth()->user() && (auth()->user()->hasRole('administrateur') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('hr') || auth()->user()->hasRole('rh'))): ?>
                <div x-data="{ open: <?php echo e(request()->is('user-management*') || request()->is('users*') || request()->is('roles*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo e(request()->is('user-management*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                        <div class="flex items-center">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('user-management*') ? 'text-primary-500' : 'text-slate-500'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Utilisateurs
                        </div>
                        <svg :class="open ? 'rotate-90' : ''" class="ml-2 h-4 w-4 text-slate-500 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="space-y-1 mt-1 px-2">
                        <a href="<?php echo e(route('user-management.index')); ?>" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->routeIs('user-management.index') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Tous les utilisateurs</a>
                        <!-- <a href="<?php echo e(route('roles.index')); ?>" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->routeIs('roles.index') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Rôles et permissions</a>
                        <a href="<?php echo e(route('user-sessions.index')); ?>" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->routeIs('user-sessions.index') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Sessions actives</a> -->
                    </div>
                </div>
                <?php endif; ?>

                <div class="pt-6 pb-2">
                    <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Opérations</p>
                </div>

                <!-- Caisses -->
                <?php if(auth()->user() && auth()->user()->canAccessModule('cash')): ?>
                <div x-data="{ open: <?php echo e(request()->is('cash*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo e(request()->is('cash*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                        <div class="flex items-center">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('cash*') ? 'text-emerald-500' : 'text-slate-500'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 0v6m-6 4h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Trésorerie
                        </div>
                        <svg :class="open ? 'rotate-90' : ''" class="ml-2 h-4 w-4 text-slate-500 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="space-y-1 mt-1 px-2">
                        <a href="/cash/registers" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->is('cash/registers*') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Caisse</a>
                        <a href="/cash/transactions" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->is('cash/transactions*') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Transactions</a>
                        <a href="/cash/natures" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->is('cash/natures*') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Nature</a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Stocks -->
                <?php if(auth()->user() && auth()->user()->canAccessModule('stock')): ?>
                <div x-data="{ open: <?php echo e(request()->is('stock*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo e(request()->is('stock*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                        <div class="flex items-center">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('stock*') ? 'text-amber-500' : 'text-slate-500'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M12 20v-8m0-8v4m0 0l-2.5 2.5M12 8l2.5 2.5" />
                            </svg>
                            Stocks
                        </div>
                        <svg :class="open ? 'rotate-90' : ''" class="ml-2 h-4 w-4 text-slate-500 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="space-y-1 mt-1 px-2">
                        <a href="/stock/warehouses" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->is('stock/warehouses*') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Entrepôts</a>
                        <a href="/stock/movements" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->is('stock/movements*') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Mouvements</a>
                        <a href="/stock/inventories" class="block px-3 py-2 pl-11 text-sm font-medium rounded-md <?php echo e(request()->is('stock/inventories*') ? 'text-white bg-primary-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">Inventaire</a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Modules Métiers -->
                <?php if(auth()->user() && (auth()->user()->canAccessModule('hr') || auth()->user()->hasRole('hr'))): ?>
                <a href="<?php echo e(route('hr.dashboard')); ?>" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo e(request()->is('hr*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('hr*') ? 'text-pink-500' : 'text-slate-500'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Ressources Humaines
                </a>
                <?php endif; ?>

                <?php if(auth()->user() && (auth()->user()->canAccessModule('accounting') || auth()->user()->hasRole('accounting'))): ?>
                <a href="<?php echo e(route('accounting.dashboard')); ?>" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo e(request()->is('accounting*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('accounting*') ? 'text-purple-500' : 'text-slate-500'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 36v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Comptabilité
                </a>
                <?php endif; ?>

                <?php if(auth()->user() && (auth()->user()->canAccessModule('purchases') || auth()->user()->hasRole('purchases'))): ?>
                <a href="<?php echo e(route('purchases.dashboard')); ?>" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo e(request()->is('purchases*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('purchases*') ? 'text-indigo-500' : 'text-slate-500'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Achats
                </a>
                <?php endif; ?>
                
                <?php if(auth()->user() && auth()->user()->canAccessModule('clients')): ?>
                <a href="<?php echo e(route('clients.dashboard')); ?>" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo e(request()->is('clients*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('clients*') ? 'text-cyan-500' : 'text-slate-500'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    CRM Clients
                </a>
                <?php endif; ?>

                <!-- Paramètres -->
                <div class="pt-6 mt-6 border-t border-slate-800">
                    <a href="<?php echo e(route('accounting.settings.index')); ?>" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 text-slate-500 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Paramètres
                    </a>
                </div>
            </nav>

            <!-- User Footer -->
            <div class="border-t border-slate-800 p-4 bg-slate-950">
                <div class="flex items-center w-full">
                    <div class="flex-shrink-0">
                        <div class="h-9 w-9 rounded-lg bg-primary-600 flex items-center justify-center text-sm font-bold text-white shadow-lg">
                            <?php echo e(auth()->user() ? substr(auth()->user()->prenom ?? 'U', 0, 1) : 'U'); ?><?php echo e(auth()->user() ? substr(auth()->user()->nom ?? 'U', 0, 1) : 'U'); ?>

                        </div>
                    </div>
                    <div class="ml-3 min-w-0 flex-1">
                        <p class="text-sm font-medium text-white truncate"><?php echo e(auth()->user() ? auth()->user()->prenom : 'Utilisateur'); ?></p>
                        <p class="text-xs text-slate-400 truncate">
                            <?php echo e(auth()->user() && auth()->user()->roles->first() ? auth()->user()->roles->first()->nom : 'Utilisateur'); ?>

                        </p>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="ml-auto">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-slate-400 hover:text-white p-1 rounded-md hover:bg-slate-800 transition-colors" title="Déconnexion">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 sticky top-0 z-30 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 shadow-sm">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" type="button" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none p-2 rounded-md hover:bg-gray-100 mr-3">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800 hidden sm:block"><?php echo $__env->yieldContent('title', 'Tableau de bord'); ?></h1>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative hidden md:block">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out" placeholder="Recherche...">
                    </div>

                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-400 hover:text-gray-500 rounded-full hover:bg-gray-100 focus:outline-none">
                        <span class="absolute top-2 right-2 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Flash Messages -->
                    <?php if(session('success')): ?>
                        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200 shadow-sm flex items-start justify-between">
                            <div class="flex">
                                <svg class="h-5 w-5 text-green-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800"><?php echo e(session('success')); ?></p>
                                </div>
                            </div>
                            <button @click="show = false" class="text-green-500 hover:text-green-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200 shadow-sm flex items-start justify-between">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800"><?php echo e(session('error')); ?></p>
                                </div>
                            </div>
                            <button @click="show = false" class="text-red-500 hover:text-red-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </main>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/layouts/app.blade.php ENDPATH**/ ?>