<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> DASHBOARD TPT-H ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-tpt.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>



</head>

<body class="bg-white">
    <!-- Loader sera injecté ici par JavaScript -->

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-white shadow-lg w-64 transition-all duration-300 ease-in-out transform">
            <div class="flex items-center justify-start space-x-1 p-4">
                <img src="/images/logo-tpt.png" alt="TPT-H ERP" class="h-12">
                <span class="text-2xl font-bold text-gray-800">TPT-H ERP</span>

            </div>
            <nav x-data="{ openMenu: '' }" class="mt-4 space-y-1">
                <a href="/"
                    class="flex items-center px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 3v1m0 16v1m8.66-11.34l-.7.7M5.04 18.96l-.7.7M21 12h-1M4 12H3m15.66 6.34l-.7-.7M5.04 5.04l-.7-.7M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="ml-3">Tableau de bord</span>
                </a>
                
<!-- GESTION DES SOCIETES -->
<div class="space-y-1">
    <button
        @click="openMenu === 'societes' ? openMenu = '' : openMenu = 'societes'"
        class="w-full flex items-center justify-between px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition focus:outline-none"
    >
        <div class="flex items-center">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 21v-8h4v8m4 0V3h4v18m4 0v-6h4v6" />
                    </svg>
            <span class="ml-3 {{ request()->is('societes*') ? 'text-red-600 font-semibold' : '' }}">
                Gestion des Sociétés
            </span>
        </div>
        <svg :class="{ 'rotate-180': openMenu === 'societes' }"
             class="h-4 w-4 transform transition-transform duration-200" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="openMenu === 'societes'" x-collapse class="ml-8 space-y-1">
        <a href="{{ route('companies.index') }}"
           class="block px-4 py-2 text-sm rounded transition {{ request()->routeIs('companies.index') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
            - Lister
        </a>
        <a href="{{ route('companies.create') }}"
           class="block px-4 py-2 text-sm rounded transition {{ request()->routeIs('companies.create') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
            - Ajouter
        </a>
    </div>
</div>
<!-- GESTION DES AGENCES -->
<div class="space-y-1">
    <button
        @click="openMenu === 'agences' ? openMenu = '' : openMenu = 'agences'"
        class="w-full flex items-center justify-between px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition focus:outline-none"
    >
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5s-3 1.343-3 3 1.343 3 3 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 22s-6-7.373-6-12a6 6 0 1112 0c0 4.627-6 12-6 12z" />
                    </svg>
            <span class="ml-3 {{ request()->is('agences*') ? 'text-red-600 font-semibold' : '' }}">
                Gestion des Agences
            </span>
        </div>
        <svg :class="{ 'rotate-180': openMenu === 'agences' }"
             class="h-4 w-4 transform transition-transform duration-200" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="openMenu === 'agences'" x-collapse class="ml-8 space-y-1">
        <a href="{{ route('agencies.index') }}"
           class="block px-4 py-2 text-sm rounded transition {{ request()->routeIs('agencies.index') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
            - Lister
        </a>
        <a href="{{ route('agencies.create') }}"
           class="block px-4 py-2 text-sm rounded transition {{ request()->routeIs('agencies.create') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
            - Ajouter
        </a>
    </div>
</div>


    <!-- GESTION UTILISATEURS -->
    <div class="space-y-1">
        <button
            @click="openMenu === 'users' ? openMenu = '' : openMenu = 'users'"
            class="w-full flex items-center justify-between px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition focus:outline-none"
        >
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5.121 17.804A10.948 10.948 0 0112 15c2.485 0 4.77.81 6.879 2.16M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="ml-3 {{ request()->is('users*') || request()->is('roles*') || request()->is('permissions*') ? 'text-red-600 font-semibold' : '' }}">
                    Gestion des utilisateurs
                </span>
            </div>
            <svg :class="{ 'rotate-180': openMenu === 'users' }"
                 class="h-4 w-4 transform transition-transform duration-200" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="openMenu === 'users'" x-collapse class="ml-8 space-y-1">
            <a href="/users"
               class="block px-4 py-2 text-sm rounded transition {{ request()->is('users*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">- Utilisateurs</a>
            <a href="/roles"
               class="block px-4 py-2 text-sm rounded transition {{ request()->is('roles*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">- Rôle </a>
            <a href="/permissions"
               class="block px-4 py-2 text-sm rounded transition {{ request()->is('permissions*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">- Permissions </a>
        </div>
    </div>

{{-- GESTION DES CAISSES --}}
   <div x-data class="space-y-1">
    <button
        @click="openMenu === 'caisses' ? openMenu = '' : openMenu = 'caisses'"
        class="w-full flex items-center justify-between px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition focus:outline-none"
    >
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 0v6m-6 4h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
            <span
                class="ml-3 {{ request()->is('cash/registers*') || request()->is('cash/natures*') || request()->is('cash/transactions*') ? 'text-red-600 font-semibold' : '' }}">
                Gestion des caisses
            </span>
        </div>
        <svg :class="{ 'rotate-180': openMenu === 'caisses' }"
             class="h-4 w-4 transform transition-transform duration-200" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="openMenu === 'caisses'" x-collapse class="ml-8 space-y-1">
        <a href="/cash/registers"
           class="block px-4 py-2 text-sm rounded transition
           {{ request()->is('cash/registers*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
          - Caisse
        </a>
        <a href="/cash/natures"
           class="block px-4 py-2 text-sm rounded transition
           {{ request()->is('cash/natures*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
           - Nature
        </a>
        <a href="/cash/transactions"
           class="block px-4 py-2 text-sm rounded transition
           {{ request()->is('cash/transactions*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
           - Transaction
        </a>
@php
    $currentSession = isset($cashRegister) ? $cashRegister->currentSession() : null;
@endphp
@if($currentSession)
    <a href="{{ route('cash.sessions.report', ['session' => $currentSession->id]) }}"
       class="block px-4 py-2 text-sm rounded transition
       {{ request()->is('cash/sessions/' . $currentSession->id . '/report*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
       - Rapport de Session
    </a>
@endif


    </div>
</div>


                {{-- <a href="/cash/registers"
                    class="flex items-center px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 0v6m-6 4h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span class="ml-3">Caisse</span>
                </a>
                <a href="/cash/natures"
                    class="flex items-center px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 0v6m-6 4h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span class="ml-3">Nature Caisse</span>
                </a>
                <a href="/cash/transactions"
                    class="flex items-center px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 0v6m-6 4h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span class="ml-3">Transation Caisse</span>
                </a> --}}

                {{-- @if(isset($session) && $session)
    <a href="/sessions/{{ $session->id }}/report"
        class="flex items-center px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 0v6m-6 4h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z" />
        </svg>
        <span class="ml-3">Rapport de Session de Caisse</span>
    </a>
@endif --}}

<!-- GESTION DES STOCKS -->
    <div class="space-y-1">
        <button
            @click="openMenu === 'stocks' ? openMenu = '' : openMenu = 'stocks'"
            class="w-full flex items-center justify-between px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition focus:outline-none"
        >
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 12H4M12 20v-8m0-8v4m0 0l-2.5 2.5M12 8l2.5 2.5" />
                    </svg>
                <span class="ml-3 {{ request()->is('stock/warehouses*') || request()->is('roles*') || request()->is('permissions*') ? 'text-red-600 font-semibold' : '' }}">
                    Gestion des Stocks
                </span>
            </div>
            <svg :class="{ 'rotate-180': openMenu === 'stocks' }"
                 class="h-4 w-4 transform transition-transform duration-200" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="openMenu === 'stocks'" x-collapse class="ml-8 space-y-1">
            <a href="/stock/warehouses"
               class="block px-4 py-2 text-sm rounded transition {{ request()->is('warehouses*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">- Stocks</a>
            <a href="/roles"
               class="block px-4 py-2 text-sm rounded transition {{ request()->is('roles*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">- Rôle </a>
            <a href="/permissions"
               class="block px-4 py-2 text-sm rounded transition {{ request()->is('permissions*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">- Permissions </a>
        </div>
    </div>


                {{-- <a href="/inventory"
                    class="flex items-center px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 12H4M12 20v-8m0-8v4m0 0l-2.5 2.5M12 8l2.5 2.5" />
                    </svg>
                    <span class="ml-3">Stocks</span>
                </a> --}}


         <div class="space-y-1">
        <button
            @click="openMenu === 'personnels' ? openMenu = '' : openMenu = 'personnels'"
            class="w-full flex items-center justify-between px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition focus:outline-none"
        >
            <div class="flex items-center">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z" />
                    </svg>
                <span class="ml-3 {{ request()->is('hr/employees/create*') || request()->is('roles*') || request()->is('permissions*') ? 'text-red-600 font-semibold' : '' }}">
                    Gestion des Personnels
                </span>
            </div>
            <svg :class="{ 'rotate-180': openMenu === 'personnels' }"
                 class="h-4 w-4 transform transition-transform duration-200" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="openMenu === 'personnels'" x-collapse class="ml-8 space-y-1">
            <a href="/hr/employees/create"
               class="block px-4 py-2 text-sm rounded transition {{ request()->is('hr/employees/create*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">- Employées</a>
            <a href="/roles"
               class="block px-4 py-2 text-sm rounded transition {{ request()->is('roles*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">- Rôle </a>
            <a href="/permissions"
               class="block px-4 py-2 text-sm rounded transition {{ request()->is('permissions*') ? 'text-red-600 font-semibold bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">- Permissions </a>
        </div>
    </div>

                {{-- <a href="/staff"
                    class="flex items-center px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z" />
                    </svg>
                    <span class="ml-3">Personnel</span>
                </a> --}}
                <a href="/accounting"
                    class="flex items-center px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2a4 4 0 00-4-4H4m6-3V5a2 2 0 00-2-2H4a2 2 0 00-2 2v2a2 2 0 002 2h4m6 7h2a4 4 0 014 4v2h-2a4 4 0 01-4-4v-2z" />
                    </svg>
                    <span class="ml-3">Comptabilité</span>
                </a>
                <a href="/settings"
                    class="flex items-center px-4 py-3 text-gray-800 hover:bg-red-50 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m0 14v1m8-7h1M4 12H3m15.36-6.36l.7.7M5.64 17.64l-.7.7M17.64 17.64l.7-.7M5.64 5.64l-.7-.7M12 6a6 6 0 100 12 6 6 0 000-12z" />
                    </svg>
                    <span class="ml-3">Paramètres</span>
                </a>
            </nav>

        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <button id="toggle-sidebar" class="text-gray-500 hover:text-gray-600 lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex-1 px-4 flex justify-between">
                        <div class="flex-1 flex">
                            <div class="w-full flex md:ml-0">
                                <div class="relative w-full">
                                    <input type="search"
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-red-500"
                                        placeholder="Rechercher...">
                                </div>
                            </div>
                        </div>
                        <div class="ml-4 flex items-center md:ml-6">
                            <!-- Notifications -->
                            <button class="p-1 rounded-full text-gray-600 hover:text-red-600 focus:outline-none">
                                <span class="sr-only">Notifications</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                            </button>

                            <!-- Profile dropdown -->
                            <!-- Profile dropdown -->
                            <div class="ml-3 relative">
                                @auth
                                    <div>
                                        <button
                                            class="max-w-xs bg-gray-100 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                            <span class="sr-only">Menu utilisateur</span>
                                            <img class="h-8 w-8 rounded-full"
                                                src="{{ auth()->user()->photo ? Storage::url(auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nom . ' ' . auth()->user()->prenom) }}"
                                                alt="">
                                        </button>
                                    </div>
                                    <!-- Menu déroulant -->
                                    <div id="user-menu-dropdown"
                                        class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                        tabindex="-1">
                                        <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                            <div class="font-medium">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}
                                            </div>
                                            <div class="text-gray-500">{{ auth()->user()->email }}</div>
                                        </div>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            role="menuitem">Mon profil</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                role="menuitem">
                                                Déconnexion
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="text-sm font-medium text-gray-700 hover:text-red-600">Connexion</a>
                                @endauth
                            </div>

                            <!-- Ajouter ce script à la fin du fichier, avant la fermeture de la balise body -->
                            <script>
                                // Toggle User Menu
                                const userMenuButton = document.getElementById('user-menu-button');
                                const userMenuDropdown = document.getElementById('user-menu-dropdown');

                                userMenuButton.addEventListener('click', function() {
                                    userMenuDropdown.classList.toggle('hidden');
                                });

                                // Close menu when clicking outside
                                document.addEventListener('click', function(event) {
                                    if (!userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                                        userMenuDropdown.classList.add('hidden');
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Toggle Sidebar
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });

        // Responsive sidebar behavior
        const sidebar = document.getElementById('sidebar');

        function handleResize() {
            if (window.innerWidth < 1024) { // lg breakpoint
                sidebar.classList.add('fixed', 'z-20', '-translate-x-full');
            } else {
                sidebar.classList.remove('fixed', 'z-20', '-translate-x-full');
            }
        }
        window.addEventListener('resize', handleResize);
        handleResize(); // Initial check
    </script>
</body>

</html>

<script>
    // Configuration AJAX globale pour afficher le loader
    document.addEventListener('DOMContentLoaded', () => {
        // Ajouter le token CSRF à toutes les requêtes AJAX
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Intercepter toutes les requêtes fetch
        const originalFetch = window.fetch;
        window.fetch = function() {
            window.loader.show();
            return originalFetch.apply(this, arguments)
                .then(response => {
                    window.loader.hide();
                    return response;
                })
                .catch(error => {
                    window.loader.hide();
                    throw error;
                });
        };

        // Gérer les erreurs AJAX globales
        window.addEventListener('error', () => {
            window.loader.hide();
        });
    });
</script>
</body>

</html>
