@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-6">

    <!-- En-tête avec message de bienvenue et actions rapides -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Bonjour, {{ auth()->user()->prenom ?? 'Utilisateur' }} ! 👋
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Voici ce qui se passe aujourd'hui dans votre entreprise.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm font-medium text-gray-700">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary-50 text-primary-700 border border-primary-100">
                {{ auth()->user()->roles->first()->nom ?? 'Utilisateur' }}
            </span>
        </div>
    </div>

    <!-- Grille de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        @if(auth()->user()->canAccessModule('cash'))
        <!-- Carte Trésorerie -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Trésorerie Globale</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">
                        {{ number_format($tresorerieConsolidee ?? 0, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-500">FCFA</span>
                    </h3>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 0v6m-6 4h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-emerald-600 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    +2.5%
                </span>
                <span class="text-gray-400 ml-2">vs mois dernier</span>
            </div>
        </div>
        @endif

        @if(auth()->user()->canAccessModule('hr'))
        <!-- Carte RH -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Masse Salariale</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">
                        {{ number_format($masseSalariale ?? 0, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-500">FCFA</span>
                    </h3>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-gray-600 font-medium">{{ $employesActifs ?? 0 }} employés actifs</span>
            </div>
        </div>
        @endif

        @if(auth()->user()->canAccessModule('inventory') || auth()->user()->canAccessModule('stock'))
        <!-- Carte Stocks -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Produits en Stock</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">
                        {{ $stockDisponible ?? 0 }}
                    </h3>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                @if(($stockAlerte ?? 0) > 0)
                    <span class="text-red-500 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ $stockAlerte }} en alerte
                    </span>
                @else
                    <span class="text-emerald-600 font-medium">Niveaux de stock optimaux</span>
                @endif
            </div>
        </div>
        @endif

        @if(auth()->user()->canAccessModule('purchases'))
        <!-- Carte Achats -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Achats du Mois</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">
                        {{ number_format($achatsMensuels ?? 0, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-500">FCFA</span>
                    </h3>
                </div>
                <div class="p-3 bg-purple-50 rounded-xl">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-purple-600 font-medium">{{ $demandesEnAttente ?? 0 }} demandes en attente</span>
            </div>
        </div>
        @endif
    </div>

    <!-- Section Graphiques et Activités -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Graphique Principal -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Aperçu Financier</h3>
                <select class="text-sm border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                    <option>Cette année</option>
                    <option>6 derniers mois</option>
                    <option>Ce mois</option>
                </select>
            </div>
            <div class="relative h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Répartition et Actions Rapides -->
        <div class="space-y-6">
            <!-- Graphique Donut -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Répartition des Activités</h3>
                <div class="relative h-48">
                    <canvas id="activityChart"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2 text-sm text-center">
                    <div class="p-2 rounded bg-gray-50">
                        <span class="block text-gray-500 text-xs">Services</span>
                        <span class="font-bold text-gray-900">35%</span>
                    </div>
                    <div class="p-2 rounded bg-gray-50">
                        <span class="block text-gray-500 text-xs">Ventes</span>
                        <span class="font-bold text-gray-900">45%</span>
                    </div>
                </div>
            </div>

            <!-- Notifications / Alertes -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Notifications</h3>
                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ count($alerts ?? []) }}</span>
                </div>
                <div class="space-y-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($alerts ?? [] as $alert)
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex-shrink-0 mt-1">
                                @if(($alert['type'] ?? 'info') == 'danger')
                                    <span class="w-2 h-2 bg-red-500 rounded-full block ring-4 ring-red-100"></span>
                                @elseif(($alert['type'] ?? 'info') == 'warning')
                                    <span class="w-2 h-2 bg-amber-500 rounded-full block ring-4 ring-amber-100"></span>
                                @else
                                    <span class="w-2 h-2 bg-primary-500 rounded-full block ring-4 ring-primary-100"></span>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-800 font-medium">{{ $alert['message'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ now()->format('H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-500 text-sm">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            Aucune nouvelle notification
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Configuration commune
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#6B7280';
    
    // Graphique Revenue
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [
                    {
                        label: 'Encaissements',
                        data: [12, 19, 15, 22, 24, 28], // Données factices si non fournies
                        backgroundColor: '#3B82F6',
                        borderRadius: 6,
                    },
                    {
                        label: 'Dépenses',
                        data: [8, 12, 10, 15, 18, 20], // Données factices
                        backgroundColor: '#E5E7EB',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 20 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 2], drawBorder: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });
    }

    // Graphique Activité
    const activityCtx = document.getElementById('activityChart');
    if (activityCtx) {
        new Chart(activityCtx, {
            type: 'doughnut',
            data: {
                labels: ['Services', 'Ventes', 'Autres'],
                datasets: [{
                    data: [35, 45, 20],
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
