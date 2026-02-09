<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>TPT-H ERP | Administration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="h-full overflow-hidden" x-data="{ sidebarOpen: false }">
    
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col h-full shadow-xl">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 bg-slate-950 px-6 border-b border-slate-800">
            <div class="flex items-center space-x-3">
                <img src="/images/logo-tpt.png" alt="TPT-H" class="h-8 w-auto bg-white rounded p-1">
                <span class="text-xl font-bold tracking-wider text-white">TPT-H ERP</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 scrollbar-thin scrollbar-thumb-slate-700">
            
            <!-- Dashboard -->
            <a href="/" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('/') || request()->is('dashboard*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('/') || request()->is('dashboard*') ? 'text-white' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Tableau de bord
            </a>

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Gestion Globale</p>
            </div>

            <!-- GESTION DES SOCIETES -->
            <?php if(auth()->user() && auth()->user()->canAccessModule('companies')): ?>
            <div x-data="{ open: <?php echo e(request()->is('companies*') || request()->is('societes*') ? 'true' : 'false'); ?> }">
                <button @click="open = !open" class="w-full group flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('companies*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('companies*') ? 'text-blue-500' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Sociétés
                    </div>
                    <svg :class="open ? 'rotate-90' : ''" class="ml-2 h-4 w-4 text-slate-500 transform transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="space-y-1 mt-1 px-2">
                    <a href="<?php echo e(route('companies.index')); ?>" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('companies.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Liste des sociétés
                    </a>
                    <a href="<?php echo e(route('companies.create')); ?>" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('companies.create') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Nouvelle société
                    </a>
                    <a href="<?php echo e(route('audit-trails.index')); ?>?entity_type=company" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('audit-trails.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Audit & Historique
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- GESTION DES AGENCES -->
            <?php if(auth()->user() && auth()->user()->canAccessModule('agencies')): ?>
            <div x-data="{ open: <?php echo e(request()->is('agencies*') || request()->is('agences*') ? 'true' : 'false'); ?> }">
                <button @click="open = !open" class="w-full group flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('agencies*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('agencies*') ? 'text-blue-500' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                        Agences
                    </div>
                    <svg :class="open ? 'rotate-90' : ''" class="ml-2 h-4 w-4 text-slate-500 transform transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="space-y-1 mt-1 px-2">
                    <a href="<?php echo e(route('agencies.index')); ?>" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('agencies.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Liste des agences
                    </a>
                    <a href="<?php echo e(route('agencies.create')); ?>" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('agencies.create') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Nouvelle agence
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- GESTION UTILISATEURS -->
            <?php if(auth()->user() && auth()->user()->canAccessModule('users')): ?>
            <div x-data="{ open: <?php echo e(request()->is('user-management*') || request()->is('users*') || request()->is('roles*') ? 'true' : 'false'); ?> }">
                <button @click="open = !open" class="w-full group flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('user-management*') || request()->is('users*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('user-management*') ? 'text-blue-500' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Utilisateurs & Rôles
                    </div>
                    <svg :class="open ? 'rotate-90' : ''" class="ml-2 h-4 w-4 text-slate-500 transform transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="space-y-1 mt-1 px-2">
                    <a href="<?php echo e(route('user-management.index')); ?>" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('user-management.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Tous les utilisateurs
                    </a>
                    <a href="<?php echo e(route('roles.index')); ?>" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('roles.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Rôles et permissions
                    </a>
                    <a href="<?php echo e(route('user-sessions.index')); ?>" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('user-sessions.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Sessions actives
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- GESTION DES CAISSES -->
            <?php if(auth()->user() && auth()->user()->canAccessModule('cash')): ?>
            <div x-data="{ open: <?php echo e(request()->is('cash*') ? 'true' : 'false'); ?> }">
                <button @click="open = !open" class="w-full group flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('cash*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('cash*') ? 'text-green-500' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 0v6m-6 4h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Gestion des caisses
                    </div>
                    <svg :class="open ? 'rotate-90' : ''" class="ml-2 h-4 w-4 text-slate-500 transform transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="space-y-1 mt-1 px-2">
                    <a href="/cash/registers" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->is('cash/registers*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Caisse
                    </a>
                    <a href="/cash/natures" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->is('cash/natures*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Nature
                    </a>
                    <a href="/cash/transactions" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->is('cash/transactions*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Transaction
                    </a>
                    <?php
                        $currentSession = isset($cashRegister) ? $cashRegister->currentSession() : null;
                    ?>
                    <?php if($currentSession): ?>
                        <a href="<?php echo e(route('cash.sessions.report', ['session' => $currentSession->id])); ?>" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->is('cash/sessions/' . $currentSession->id . '/report*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                            Rapport de Session
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- GESTION DES STOCKS -->
            <?php if(auth()->user() && auth()->user()->canAccessModule('stock')): ?>
            <div x-data="{ open: <?php echo e(request()->is('stock*') ? 'true' : 'false'); ?> }">
                <button @click="open = !open" class="w-full group flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('stock*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('stock*') ? 'text-yellow-500' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M12 20v-8m0-8v4m0 0l-2.5 2.5M12 8l2.5 2.5" />
                        </svg>
                        Gestion des Stocks
                    </div>
                    <svg :class="open ? 'rotate-90' : ''" class="ml-2 h-4 w-4 text-slate-500 transform transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="space-y-1 mt-1 px-2">
                    <a href="/stock/warehouses" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->is('stock/warehouses*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Stocks
                    </a>
                    <a href="/stock/movements" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->is('stock/movements*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Mouvement
                    </a>
                    <a href="/stock/transfers" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->is('stock/transfers*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Transfert
                    </a>
                    <a href="/stock/alerts" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->is('stock/alerts*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Alerte
                    </a>
                    <a href="/stock/inventories" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->is('stock/inventories*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Inventaire
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- GESTION DES PERSONNELS -->
            <?php if(auth()->user() && (auth()->user()->canAccessModule('hr') || auth()->user()->hasRole('hr'))): ?>
            <div x-data="{ open: <?php echo e(request()->is('hr/employees*') ? 'true' : 'false'); ?> }">
                <button @click="open = !open" class="w-full group flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('hr/employees*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('hr/employees*') ? 'text-amber-500' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z" />
                        </svg>
                        Gestion des Personnels
                    </div>
                    <svg :class="open ? 'rotate-90' : ''" class="ml-2 h-4 w-4 text-slate-500 transform transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="space-y-1 mt-1 px-2">
                    <a href="/hr/employees/create" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->is('hr/employees/create*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Employés
                    </a>
                    <a href="/roles" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->is('roles*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Rôles
                    </a>
                    <a href="/permissions" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->is('permissions*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?> pl-11">
                        Permissions
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Modules Opérationnels</p>
            </div>

            <!-- RH -->
            <?php if(auth()->user() && (auth()->user()->canAccessModule('hr') || auth()->user()->hasRole('hr'))): ?>
            <a href="<?php echo e(route('hr.dashboard')); ?>" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('hr*') && !request()->is('hr/employees*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('hr*') && !request()->is('hr/employees*') ? 'text-amber-500' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Ressources Humaines
            </a>
            <?php endif; ?>

            <!-- Comptabilité -->
            <?php if(auth()->user() && (auth()->user()->canAccessModule('accounting') || auth()->user()->hasRole('accounting'))): ?>
            <a href="<?php echo e(route('accounting.dashboard')); ?>" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('accounting*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('accounting*') ? 'text-emerald-500' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 36v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Comptabilité
            </a>
            <?php endif; ?>

            <!-- Achats -->
            <?php if(auth()->user() && (auth()->user()->canAccessModule('purchases') || auth()->user()->hasRole('purchases'))): ?>
            <a href="<?php echo e(route('purchases.dashboard')); ?>" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('purchases*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('purchases*') ? 'text-purple-500' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Achats
            </a>
            <?php endif; ?>

            <!-- Fournisseurs -->
            <?php if(auth()->user() && (auth()->user()->canAccessModule('suppliers') || auth()->user()->hasRole('supplier'))): ?>
            <a href="<?php echo e(route('fournisseurs.index')); ?>" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('fournisseurs*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('fournisseurs*') ? 'text-rose-500' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Fournisseurs
            </a>
            <?php endif; ?>
            
            <!-- Clients -->
             <?php if(auth()->user() && auth()->user()->canAccessModule('clients')): ?>
            <a href="<?php echo e(route('clients.dashboard')); ?>" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('clients*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('clients*') ? 'text-cyan-500' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Clients CRM
            </a>
            <?php endif; ?>

            <?php if(auth()->user() && (auth()->user()->canAccessModule('services') || auth()->user()->hasRole('admin'))): ?>
            <a href="<?php echo e(route('services.index')); ?>" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo e(request()->is('services*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?> transition-all duration-200">
                <svg class="mr-3 h-5 w-5 flex-shrink-0 <?php echo e(request()->is('services*') ? 'text-blue-500' : 'text-slate-400 group-hover:text-white'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Services
            </a>
            <?php endif; ?>
            
            <!-- Paramètres -->
            <a href="/settings" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200">
                <svg class="mr-3 h-5 w-5 flex-shrink-0 text-slate-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m8-7h1M4 12H3m15.36-6.36l.7.7M5.64 17.64l-.7.7M17.64 17.64l.7-.7M5.64 5.64l-.7-.7M12 6a6 6 0 100 12 6 6 0 000-12z" />
                </svg>
                Paramètres
            </a>

        </nav>
        
        <!-- User Footer -->
        <div class="border-t border-slate-800 p-4 bg-slate-950">
            <div class="flex items-center w-full">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-medium text-white">
                        <?php echo e(auth()->user() ? substr(auth()->user()->prenom ?? 'U', 0, 1) : 'U'); ?><?php echo e(auth()->user() ? substr(auth()->user()->nom ?? 'U', 0, 1) : 'U'); ?>

                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white"><?php echo e(auth()->user() ? auth()->user()->prenom : 'Utilisateur'); ?> <?php echo e(auth()->user() ? auth()->user()->nom : 'Anonyme'); ?></p>
                    <p class="text-xs font-medium text-slate-400 group-hover:text-slate-300 truncate w-32">
                        <?php echo e(auth()->user() && auth()->user()->roles->first() ? auth()->user()->roles->first()->nom : 'Utilisateur'); ?>

                    </p>
                </div>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="ml-auto">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="text-slate-400 hover:text-white transition-colors" title="Déconnexion">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-[300px] overflow-hidden bg-gray-50">
        
        <!-- Top Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 z-10">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <button @click="sidebarOpen = true" type="button" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <div class="flex-1 flex justify-between items-center ml-4 xl:ml-0">
                        <div class="flex-1 flex max-w-lg ml-4 xl:ml-8">
                            <label for="search-field" class="sr-only">Rechercher</label>
                            <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input id="search-field" class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent sm:text-sm" placeholder="Rechercher partout..." type="search" name="search">
                            </div>
                        </div>
                        
                        <div class="ml-4 flex items-center md:ml-6 space-x-4">
                            <!-- Notifications -->
                            <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 relative">
                                <span class="sr-only">Notifications</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto focus:outline-none p-6">
            <?php if(session('success')): ?>
                <div class="mb-6 rounded-md bg-green-50 p-4 border-l-4 border-green-400 shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800"><?php echo e(session('success')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-6 rounded-md bg-red-50 p-4 border-l-4 border-red-400 shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800"><?php echo e(session('error')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    
    <!-- Scripts supplémentaires -->
    <!-- Scripts placés avant la fermeture de body pour de meilleures performances -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>

<?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\layouts\app.blade.php ENDPATH**/ ?>