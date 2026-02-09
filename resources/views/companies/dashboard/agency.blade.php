@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $agency->nom }}</h1>
            <p class="text-gray-600">Agence de {{ $agency->company->raison_sociale }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('audit-trails.agency', $agency->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                Historique
            </a>
            <a href="{{ route('agencies.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Agency Info -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Informations Générales</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Nom:</span> {{ $agency->nom }}</p>
                    <p><span class="font-medium">Code unique:</span> {{ $agency->code_unique }}</p>
                    <p><span class="font-medium">Société:</span> 
                        <a href="{{ route('companies.dashboard.company', $agency->company->id) }}" class="text-primary-600 hover:text-primary-800">
                            {{ $agency->company->raison_sociale }}
                        </a>
                    </p>
                    <p><span class="font-medium">Zone géographique:</span> {{ $agency->zone_geographique }}</p>
                    <p><span class="font-medium">Statut:</span> 
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $agency->statut === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $agency->statut === 'active' ? 'Active' : 'En veille' }}
                        </span>
                    </p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Responsable</h3>
                <div class="space-y-2">
                    @if($agency->responsable)
                        <p><span class="font-medium">Nom:</span> {{ $agency->responsable->name }}</p>
                        <p><span class="font-medium">Email:</span> {{ $agency->responsable->email }}</p>
                    @else
                        <p class="text-gray-500">Aucun responsable assigné</p>
                    @endif
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Coordonnées</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Adresse:</span> {{ $agency->adresse }}</p>
                    @if($agency->latitude && $agency->longitude)
                        <p><span class="font-medium">Coordonnées GPS:</span> {{ $agency->latitude }}, {{ $agency->longitude }}</p>
                        <div class="mt-2">
                            <a href="https://www.google.com/maps?q={{ $agency->latitude }},{{ $agency->longitude }}" target="_blank" class="text-primary-600 hover:text-primary-800 text-sm">
                                Voir sur la carte
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Résumé Financier</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Solde Total des Caisses</p>
                        <p class="text-xl font-bold">{{ number_format($totalBalance, 2, ',', ' ') }} {{ $agency->company->devise }}</p>
                    </div>
                </div>
            </div>
            
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-primary-100 text-primary-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Nombre de Caisses</p>
                        <p class="text-xl font-bold">{{ $cashRegisters->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Encaissements</p>
                        <p class="text-xl font-bold">{{ number_format($encaissements, 2, ',', ' ') }} {{ $agency->company->devise }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-indigo-100 text-indigo-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Flux de Trésorerie Net</p>
                        <p class="text-xl font-bold">{{ number_format($netCashFlow, 2, ',', ' ') }} {{ $agency->company->devise }}</p>
                    </div>
                </div>
            </div>
            
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-pink-100 text-pink-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Nombre de Transactions</p>
                        <p class="text-xl font-bold">{{ $transactionCount }}</p>
                    </div>
                </div>
            </div>
            
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-teal-100 text-teal-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Décaissements</p>
                        <p class="text-xl font-bold">{{ number_format($decaissements, 2, ',', ' ') }} {{ $agency->company->devise }}</p>
                    </div>
                </div>
            </div>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Détails</th>
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
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @if($activity->description)
                                        {{ $activity->description }}
                                    @else
                                        @if(is_array($activity->changes))
                                            @foreach($activity->changes as $field => $value)
                                                <span class="font-medium">{{ $field }}:</span> {{ $value }}<br>
                                            @endforeach
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4 text-gray-500">
                <p>Aucune activité récente pour cette agence.</p>
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

    <!-- Entity-Specific Parameters -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Bank Accounts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Comptes Bancaires</h2>
                <a href="{{ route('bank-accounts.index') }}" class="text-primary-600 hover:text-primary-800 text-sm">
                    Voir tout
                </a>
            </div>
            @if($bankAccounts->count() > 0)
                <div class="space-y-3">
                    @foreach($bankAccounts as $account)
                        <div class="border rounded-lg p-3">
                            <p class="font-medium text-gray-900">{{ $account->bank_name }}</p>
                            <p class="text-sm text-gray-600">{{ $account->account_number }}</p>
                            <p class="text-sm text-gray-500">{{ $account->account_type }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-gray-500">
                    <p>Aucun compte bancaire enregistré.</p>
                </div>
            @endif
        </div>
        
        <!-- Policies -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Politiques Internes</h2>
                <a href="{{ route('policies.index') }}" class="text-primary-600 hover:text-primary-800 text-sm">
                    Voir tout
                </a>
            </div>
            @if($policies->count() > 0)
                <div class="space-y-3">
                    @foreach($policies as $policy)
                        <div class="border rounded-lg p-3">
                            <p class="font-medium text-gray-900">{{ $policy->title }}</p>
                            <p class="text-sm text-gray-600">{{ Str::limit($policy->description, 50) }}</p>
                            <p class="text-xs text-gray-500">Mis à jour le {{ $policy->updated_at->format('d/m/Y') }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-gray-500">
                    <p>Aucune politique enregistrée.</p>
                </div>
            @endif
        </div>
        
        <!-- Tax Regulations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Réglementations Fiscales</h2>
                <a href="{{ route('tax-regulations.index') }}" class="text-primary-600 hover:text-primary-800 text-sm">
                    Voir tout
                </a>
            </div>
            @if($taxRegulations->count() > 0)
                <div class="space-y-3">
                    @foreach($taxRegulations as $regulation)
                        <div class="border rounded-lg p-3">
                            <p class="font-medium text-gray-900">{{ $regulation->name }}</p>
                            <p class="text-sm text-gray-600">{{ Str::limit($regulation->description, 50) }}</p>
                            <p class="text-xs text-gray-500">Taux: {{ $regulation->tax_rate }}%</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-gray-500">
                    <p>Aucune réglementation fiscale enregistrée.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Financial Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Transactions Récentes</h2>
            @if($recentTransactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-primary-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Caisse</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Montant</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $transaction->cashRegister->name }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $transaction->type === 'encaissement' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $transaction->type === 'encaissement' ? 'Encaissement' : 'Décaissement' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ number_format($transaction->montant, 2, ',', ' ') }} {{ $transaction->cashRegister->currency }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-gray-500">
                    <p>Aucune transaction récente.</p>
                </div>
            @endif
        </div>

        <!-- Cash Flow Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Flux de Trésorerie</h2>
            <div class="h-64">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Cash Flow Chart
    const cashFlowCtx = document.getElementById('cashFlowChart').getContext('2d');
    const cashFlowChart = new Chart(cashFlowCtx, {
        type: 'bar',
        data: {
            labels: ['Encaissements', 'Décaissements', 'Flux Net'],
            datasets: [{
                label: 'Montant ({{ $agency->company->devise }})',
                data: [{{ $encaissements }}, {{ $decaissements }}, {{ $netCashFlow }}],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
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