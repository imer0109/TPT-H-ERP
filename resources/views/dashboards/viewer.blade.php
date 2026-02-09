@extends('layouts.app')

@section('title', 'Tableau de bord Consultant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de bord Consultant</h1>
        <p class="text-gray-600 mt-2">Vue d'ensemble des activités et rapports</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-primary-500">
            <h3 class="text-gray-500 text-sm font-medium">Rapports Consultés</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['totalReports'] }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-medium">Projets Actifs</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['activeProjects'] }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
            <h3 class="text-gray-500 text-sm font-medium">Projets Terminés</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['completedProjects'] }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm font-medium">Relectures en Attente</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['pendingReviews'] }}</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistiques Par Domaine</h3>
            <div class="space-y-4">
                @foreach($data['stats'] as $stat)
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $stat['label'] }}</span>
                        <span class="text-sm font-medium text-gray-700">{{ $stat['value'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <?php $width = $stat['value']; ?><div class="bg-primary-600 h-2.5 rounded-full" style="width: <?php echo $width; ?>%;"></div>
                    </div>
                    <div class="mt-1 text-xs text-gray-500">
                        @if($stat['change'] >= 0)
                            <span class="text-green-500">↑ {{ $stat['change'] }}%</span> par rapport au mois dernier
                        @else
                            <span class="text-red-500">↓ {{ abs($stat['change']) }}%</span> par rapport au mois dernier
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Activités Récentes</h3>
            <div class="space-y-4">
                @foreach($data['recentActivities'] as $activity)
                <div class="flex items-start">
                    <div class="bg-primary-100 p-2 rounded-full mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $activity['activity'] }}</p>
                        <p class="text-xs text-gray-500">{{ $activity['date'] }} par {{ $activity['user'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Reports Section -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Rapports Disponibles</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="#" class="border border-gray-200 rounded-lg p-4 hover:border-primary-300 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-primary-100 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Rapport RH</h4>
                        <p class="text-xs text-gray-500">Statistiques des employés</p>
                    </div>
                </div>
            </a>
            
            <a href="#" class="border border-gray-200 rounded-lg p-4 hover:border-primary-300 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Rapport Financier</h4>
                        <p class="text-xs text-gray-500">Analyse budgétaire</p>
                    </div>
                </div>
            </a>
            
            <a href="#" class="border border-gray-200 rounded-lg p-4 hover:border-primary-300 hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Rapport Clients</h4>
                        <p class="text-xs text-gray-500">Analyse de satisfaction</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
