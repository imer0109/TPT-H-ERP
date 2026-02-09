@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-tachometer-alt text-red-600"></i>
            Tableau de Bord API Connectors
        </h1>
        <a href="{{ route('api-connectors.api-connectors.index') }}" 
           class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md flex items-center gap-2 transition-all duration-300">
            <i class="fas fa-list"></i> Voir tous les connecteurs
        </a>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-gradient-to-r from-primary-600 to-primary-500 text-white p-5 rounded-2xl shadow-md hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm opacity-80">Total Connecteurs</p>
                    <h2 class="text-3xl font-bold">{{ $stats['total_connectors'] }}</h2>
                </div>
                <i class="fas fa-plug text-4xl opacity-70"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-600 to-green-500 text-white p-5 rounded-2xl shadow-md hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm opacity-80">Connecteurs Actifs</p>
                    <h2 class="text-3xl font-bold">{{ $stats['active_connectors'] }}</h2>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-70"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-cyan-600 to-cyan-500 text-white p-5 rounded-2xl shadow-md hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm opacity-80">Synchronisations (24h)</p>
                    <h2 class="text-3xl font-bold">{{ $stats['recent_syncs'] }}</h2>
                </div>
                <i class="fas fa-sync text-4xl opacity-70"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-600 to-red-500 text-white p-5 rounded-2xl shadow-md hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm opacity-80">Échecs (24h)</p>
                    <h2 class="text-3xl font-bold">{{ $stats['failed_syncs'] }}</h2>
                </div>
                <i class="fas fa-exclamation-triangle text-4xl opacity-70"></i>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Activité récente -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4 flex items-center gap-2">
                <i class="fas fa-history text-red-600"></i>
                <h2 class="font-semibold text-gray-800">Activité Récente</h2>
            </div>
            <div class="p-6 overflow-x-auto">
                @if($recentLogs->count() > 0)
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-sm text-gray-600 uppercase">
                                <th class="px-4 py-3">Connecteur</th>
                                <th class="px-4 py-3">Société</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Durée</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentLogs as $log)
                            <tr class="hover:bg-primary-50 transition">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $log->connector->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $log->connector->company->raison_sociale ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 text-xs rounded-full bg-primary-100 text-primary-700 font-semibold">
                                        {{ $log->connector->getTypeLabel() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 text-xs rounded-full font-semibold 
                                        {{ $log->status === 'success' ? 'bg-green-100 text-green-700' : 
                                           ($log->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $log->started_at->format('d/m/Y H:i') }}
                                    <br><span class="text-xs text-gray-400">{{ $log->started_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    @if($log->finished_at)
                                        {{ $log->finished_at->diffInSeconds($log->started_at) }}s
                                    @else
                                        <span class="text-gray-400 italic">En cours</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-10 text-gray-500">
                        <i class="fas fa-info-circle text-4xl mb-3 text-primary-400"></i>
                        <h3 class="text-lg font-semibold">Aucune activité récente</h3>
                        <p>Aucune synchronisation effectuée ces dernières 24h.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Graphique -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-red-600"></i>
                <h2 class="font-semibold text-gray-800">Répartition par Type</h2>
            </div>
            <div class="p-6">
                @if($connectorsByType->count() > 0)
                    <canvas id="connectorsByTypeChart" height="300"></canvas>
                @else
                    <div class="text-center py-10 text-gray-500">
                        <i class="fas fa-info-circle text-4xl mb-3 text-primary-400"></i>
                        <h3 class="text-lg font-semibold">Aucun connecteur</h3>
                        <p>Aucun connecteur n'a encore été configuré.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($connectorsByType->count() > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('connectorsByTypeChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [
                    @foreach($connectorsByType as $type => $count)
                        '{{ \App\Models\ApiConnector::getConnectorTypes()[$type] ?? $type }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($connectorsByType as $type => $count)
                            {{ $count }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f97316', '#9ca3af'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>
@endpush
@endif
@endsection
