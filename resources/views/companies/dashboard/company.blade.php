@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $company->raison_sociale }}</h1>
            <p class="text-gray-600">{{ $company->type === 'holding' ? 'Société mère' : 'Filiale' }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('audit-trails.company', $company->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                Historique
            </a>
            <a href="{{ route('companies.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Company Info -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Informations Générales</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Raison sociale:</span> {{ $company->raison_sociale }}</p>
                    <p><span class="font-medium">Type:</span> 
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $company->type === 'holding' ? 'bg-primary-100 text-primary-800' : 'bg-green-100 text-green-800' }}">
                            {{ $company->type === 'holding' ? 'Holding' : 'Filiale' }}
                        </span>
                    </p>
                    <p><span class="font-medium">Secteur d'activité:</span> {{ $company->secteur_activite }}</p>
                    <p><span class="font-medium">Devise:</span> {{ $company->devise }}</p>
                    <p><span class="font-medium">Statut:</span> 
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $company->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $company->active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Coordonnées</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Pays:</span> {{ $company->pays }}</p>
                    <p><span class="font-medium">Ville:</span> {{ $company->ville }}</p>
                    <p><span class="font-medium">Siège social:</span> {{ $company->siege_social }}</p>
                    <p><span class="font-medium">Email:</span> {{ $company->email ?? '-' }}</p>
                    <p><span class="font-medium">Téléphone:</span> {{ $company->telephone ?? '-' }}</p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Documents Légaux</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">NIU:</span> {{ $company->niu ?? '-' }}</p>
                    <p><span class="font-medium">RCCM:</span> {{ $company->rccm ?? '-' }}</p>
                    <p><span class="font-medium">Régime fiscal:</span> {{ $company->regime_fiscal ?? '-' }}</p>
                    <p><span class="font-medium">Site web:</span> 
                        @if($company->site_web)
                            <a href="{{ $company->site_web }}" target="_blank" class="text-primary-600 hover:text-primary-800">
                                {{ $company->site_web }}
                            </a>
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        @if($company->logo || $company->visuel)
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Visuels</h3>
            <div class="flex space-x-4">
                @if($company->logo)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Logo</p>
                        <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="h-20 w-20 object-cover rounded">
                    </div>
                @endif
                @if($company->visuel)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Visuel</p>
                        <img src="{{ Storage::url($company->visuel) }}" alt="Visuel" class="h-20 w-20 object-cover rounded">
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Financial Summary -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Résumé Financier</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="border rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Solde Total des Caisses</p>
                        <p class="text-xl font-bold">{{ number_format($totalBalance, 2, ',', ' ') }} {{ $company->devise }}</p>
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
                    <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Nombre d'Agences</p>
                        <p class="text-xl font-bold">{{ $agencies->count() }}</p>
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
                        <p class="text-xl font-bold">{{ number_format($encaissements, 2, ',', ' ') }} {{ $company->devise }}</p>
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
                        <p class="text-xl font-bold">{{ number_format($netCashFlow, 2, ',', ' ') }} {{ $company->devise }}</p>
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
                        <p class="text-sm font-medium text-gray-600">Solde Moyen par Caisse</p>
                        <p class="text-xl font-bold">{{ number_format($averageTransaction, 2, ',', ' ') }} {{ $company->devise }}</p>
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
                        <p class="text-xl font-bold">{{ number_format($decaissements, 2, ',', ' ') }} {{ $company->devise }}</p>
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
                <p>Aucune activité récente pour cette société.</p>
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

    <!-- Subsidiaries Comparison -->
    @if($company->isHolding() && count($subsidiariesData) > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Comparatif des Filiales</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Filiale</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Solde Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Encaissements</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Décaissements</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Nombre d'Agences</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($subsidiariesData as $subsidiary)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $subsidiary['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($subsidiary['balance'], 2, ',', ' ') }} {{ $company->devise }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($subsidiary['encaissements'], 2, ',', ' ') }} {{ $company->devise }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($subsidiary['decaissements'], 2, ',', ' ') }} {{ $company->devise }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subsidiary['agencies_count'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

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
</div>
@endsection