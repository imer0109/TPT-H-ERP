@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de bord des Entités</h1>
        <a href="{{ route('audit-trails.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
            Historique Global
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-primary-100 text-primary-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Sociétés Actives</p>
                    <p class="text-2xl font-bold">{{ $activeCompanies }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Sociétés Inactives</p>
                    <p class="text-2xl font-bold">{{ $inactiveCompanies }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Agences Actives</p>
                    <p class="text-2xl font-bold">{{ $activeAgencies }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100 text-gray-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Agences en Veille</p>
                    <p class="text-2xl font-bold">{{ $standbyAgencies }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Holdings and Subsidiaries -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Arborescence des Entités</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Société</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Secteur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Pays</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Filiale(s)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Agence(s)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($holdings as $holding)
                        <tr class="bg-primary-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($holding->logo)
                                        <img src="{{ Storage::url($holding->logo) }}" class="h-8 w-8 rounded-full mr-3 object-cover">
                                    @else
                                        <div class="h-8 w-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center text-gray-500">N/A</div>
                                    @endif
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('companies.dashboard.company', $holding->id) }}" class="text-primary-600 hover:text-primary-900">
                                            {{ $holding->raison_sociale }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                                    Holding
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $holding->secteur_activite }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $holding->pays }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $holding->filiales->count() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $holding->agencies->count() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $holding->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $holding->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        
                        @foreach($holding->filiales as $filiale)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center ml-8">
                                        @if($filiale->logo)
                                            <img src="{{ Storage::url($filiale->logo) }}" class="h-8 w-8 rounded-full mr-3 object-cover">
                                        @else
                                            <div class="h-8 w-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center text-gray-500">N/A</div>
                                        @endif
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('companies.dashboard.company', $filiale->id) }}" class="text-primary-600 hover:text-primary-900">
                                                {{ $filiale->raison_sociale }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Filiale
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $filiale->secteur_activite }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $filiale->pays }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $filiale->agencies->count() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $filiale->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $filiale->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Activités Récentes</h2>
        @if($recentActivities->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Entité</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentActivities as $activity)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $activity->user ? $activity->user->name : 'Système' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                                        {{ ucfirst($activity->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($activity->entity)
                                        @if($activity->entity_type === 'company')
                                            <a href="{{ route('companies.dashboard.company', $activity->entity->id) }}" class="text-primary-600 hover:text-primary-900">
                                                {{ $activity->entity->raison_sociale }}
                                            </a>
                                        @elseif($activity->entity_type === 'agency')
                                            <a href="{{ route('companies.dashboard.agency', $activity->entity->id) }}" class="text-primary-600 hover:text-primary-900">
                                                {{ $activity->entity->nom }}
                                            </a>
                                        @endif
                                    @else
                                        Entité supprimée
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4 text-gray-500">
                <p>Aucune activité récente.</p>
            </div>
        @endif
    </div>

    <!-- Alerts -->
    @if(count($alerts) > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Alertes & Notifications</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($alerts as $alert)
                    <div class="p-4 rounded-lg border 
                        @if($alert['type'] === 'danger') bg-red-50 border-red-200
                        @elseif($alert['type'] === 'warning') bg-yellow-50 border-yellow-200
                        @elseif($alert['type'] === 'info') bg-primary-50 border-primary-200
                        @endif">
                        <div class="flex items-start">
                            <i class="{{ $alert['icon'] }} 
                                @if($alert['type'] === 'danger') text-red-600
                                @elseif($alert['type'] === 'warning') text-yellow-600
                                @elseif($alert['type'] === 'info') text-primary-600
                                @endif mr-3 mt-1"></i>
                            <div>
                                <h3 class="text-sm font-medium 
                                    @if($alert['type'] === 'danger') text-red-800
                                    @elseif($alert['type'] === 'warning') text-yellow-800
                                    @elseif($alert['type'] === 'info') text-primary-800
                                    @endif">
                                    {{ $alert['message'] }}
                                </h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Comparative Chart for Subsidiaries -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Comparatif des Filiales</h2>
            <div class="h-64">
                <canvas id="comparativeChart"></canvas>
            </div>
        </div>
        
        <!-- Companies by Sector -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Sociétés par Secteur d'Activité</h2>
            <div class="h-64">
                <canvas id="sectorChart"></canvas>
            </div>
        </div>

        <!-- Companies by Country -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Sociétés par Pays</h2>
            <div class="h-64">
                <canvas id="countryChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sector Chart
    const sectorCtx = document.getElementById('sectorChart').getContext('2d');
    const sectorChart = new Chart(sectorCtx, {
        type: 'pie',
        data: {
            labels: [@foreach($companiesBySector as $sector)'{{ $sector->secteur_activite }}',@endforeach],
            datasets: [{
                data: [@foreach($companiesBySector as $sector){{ $sector->count }},@endforeach],
                backgroundColor: [
                    '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Country Chart
    const countryCtx = document.getElementById('countryChart').getContext('2d');
    const countryChart = new Chart(countryCtx, {
        type: 'bar',
        data: {
            labels: [@foreach($companiesByCountry as $country)'{{ $country->pays }}',@endforeach],
            datasets: [{
                label: 'Nombre de sociétés',
                data: [@foreach($companiesByCountry as $country){{ $country->count }},@endforeach],
                backgroundColor: '#3B82F6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Comparative Chart for Subsidiaries
    const comparativeCtx = document.getElementById('comparativeChart').getContext('2d');
    const comparativeChart = new Chart(comparativeCtx, {
        type: 'bar',
        data: {
            labels: [@foreach($holdings as $holding)@foreach($holding->filiales as $filiale)'{{ $filiale->raison_sociale }}',@endforeach @endforeach],
            datasets: [{
                label: 'Nombre d\'agences',
                data: [@foreach($holdings as $holding)@foreach($holding->filiales as $filiale){{ $filiale->agencies->count() }},@endforeach @endforeach],
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection