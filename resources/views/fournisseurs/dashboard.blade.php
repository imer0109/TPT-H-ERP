@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Fournisseurs</h1>
        <div class="text-sm text-gray-500">
            Mis à jour le {{ now()->format('d/m/Y à H:i') }}
        </div>
    </div>

    <!-- Alertes importantes -->
    @if($overdueInvoices > 0 || $openIssues > 0)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Attention requise</h3>
                    <div class="mt-2 text-sm text-red-700">
                        @if($overdueInvoices > 0)
                            <p>{{ $overdueInvoices }} facture(s) en retard ({{ number_format($overdueAmount, 0, ',', ' ') }} XAF)</p>
                        @endif
                        @if($openIssues > 0)
                            <p>{{ $openIssues }} réclamation(s) ouverte(s)</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Fournisseurs -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-primary-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Fournisseurs</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalSuppliers }}</p>
                    <p class="text-xs text-gray-500">{{ $activeSuppliers }} actifs</p>
                </div>
            </div>
        </div>

        <!-- Commandes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Commandes</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalOrders }}</p>
                    <p class="text-xs text-gray-500">{{ $pendingOrders }} en attente</p>
                </div>
            </div>
        </div>

        <!-- Paiements -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Paiements</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalPayments, 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500">{{ number_format($paymentsThisMonth, 0, ',', ' ') }} ce mois</p>
                </div>
            </div>
        </div>

        <!-- Réclamations -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Réclamations</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalIssues }}</p>
                    <p class="text-xs text-gray-500">{{ $openIssues }} ouvertes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Répartition par type d'activité -->
    <div class="mt-6 bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Répartition par type d'activité</h3>
        </div>
        <div class="p-6">
            @if($suppliersByActivity->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($suppliersByActivity as $activity)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $activity->activite }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    {{ $activity->count }}
                                </span>
                            </div>
                            <div class="mt-2 bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" 
                                     style="width: {{ ($activity->count / $totalSuppliers) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Top fournisseurs -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Top fournisseurs par volume</h3>
            </div>
            <div class="p-6">
                @if($topSuppliers->count() > 0)
                    <div class="space-y-4">
                        @foreach($topSuppliers as $supplier)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $supplier->fournisseur->raison_sociale }}</p>
                                    <p class="text-xs text-gray-500">{{ $supplier->order_count }} commande(s)</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ number_format($supplier->total_amount, 0, ',', ' ') }} XAF</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucune donnée disponible...</p>
                @endif
            </div>
        </div>

        <!-- Fournisseurs à risque -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Fournisseurs à risque</h3>
            </div>
            <div class="p-6">
                @if($riskySuppliers->count() > 0)
                    <div class="space-y-4">
                        @foreach($riskySuppliers as $supplier)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $supplier->raison_sociale }}</p>
                                    <p class="text-xs text-gray-500">{{ $supplier->supplier_orders_count }} commande(s), {{ $supplier->supplier_issues_count }} réclamation(s)</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Risque
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun fournisseur à risque</p>
                @endif
            </div>
        </div>
        
        <!-- Fournisseurs les mieux notés -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Fournisseurs les mieux notés</h3>
            </div>
            <div class="p-6">
                @if($topRatedSuppliers->count() > 0)
                    <div class="space-y-4">
                        @foreach($topRatedSuppliers as $supplier)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $supplier->raison_sociale }}</p>
                                    <div class="flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if($i <= round($supplier->supplier_ratings_avg_overall_score))
                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endif
                                        @endfor
                                        <span class="text-xs text-gray-500 ml-1">({{ number_format($supplier->supplier_ratings_avg_overall_score, 1) }}/5)</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $supplier->supplier_ratings_count }} évaluation(s)</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucune évaluation disponible</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Évolution des dépenses -->
    @if($monthlyExpenses->count() > 0)
        <div class="mt-6 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Évolution des dépenses (6 derniers mois)</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($monthlyExpenses as $expense)
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">{{ \Carbon\Carbon::createFromFormat('Y-m', $expense->month)->format('M Y') }}</span>
                                    <span class="font-medium">{{ number_format($expense->total, 0, ',', ' ') }} XAF</span>
                                </div>
                                <div class="mt-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: {{ ($expense->total / $monthlyExpenses->max('total')) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    
    <!-- Contrats expirant bientôt -->
    @if($expiringContracts->count() > 0)
        <div class="mt-6 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Contrats expirant bientôt</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($expiringContracts as $contract)
                        <div class="flex items-center justify-between p-3 border rounded-lg {{ $contract->days_until_expiry <= 7 ? 'border-red-300 bg-red-50' : 'border-yellow-300 bg-yellow-50' }}">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('fournisseurs.contracts.show', $contract) }}" class="text-primary-600 hover:text-primary-800">
                                        {{ $contract->contract_number }}
                                    </a>
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $contract->fournisseur->raison_sociale }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $contract->end_date->format('d/m/Y') }}
                                </p>
                                <p class="text-xs {{ $contract->days_until_expiry <= 7 ? 'text-red-600' : 'text-yellow-600' }}">
                                    Dans {{ $contract->days_until_expiry }} jours
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Chart.js for enhanced visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // You can add more advanced chart visualizations here if needed
        console.log('Dashboard loaded');
    });
</script>
@endsection