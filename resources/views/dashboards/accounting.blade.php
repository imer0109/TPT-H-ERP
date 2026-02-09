@extends('layouts.app')

@section('title', 'Tableau de bord Comptabilité')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de bord Comptabilité</h1>
        <p class="text-gray-600 mt-2">Vue d'ensemble des finances et transactions</p>
    </div>

    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-medium">Revenus Totals</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($data['totalRevenue'], 0, '', ' ') }} FCFA</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
            <h3 class="text-gray-500 text-sm font-medium">Dépenses Totales</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($data['totalExpenses'], 0, '', ' ') }} FCFA</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-primary-500">
            <h3 class="text-gray-500 text-sm font-medium">Bénéfice Net</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($data['netProfit'], 0, '', ' ') }} FCFA</p>
        </div>
    </div>

    <!-- Invoices and Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Factures</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-primary-50 rounded">
                    <span class="font-medium">Factures en attente</span>
                    <span class="bg-primary-100 text-primary-800 px-2 py-1 rounded-full text-sm">{{ $data['pendingInvoices'] }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-orange-50 rounded">
                    <span class="font-medium">Factures impayées</span>
                    <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-sm">{{ $data['overdueInvoices'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Transactions Récentes</h3>
            <div class="space-y-3">
                @foreach($data['recentTransactions'] as $transaction)
                <div class="flex justify-between items-center border-b pb-2">
                    <div>
                        <p class="font-medium text-gray-800">{{ $transaction['description'] }}</p>
                        <p class="text-xs text-gray-500">{{ $transaction['date'] }}</p>
                    </div>
                    <span class="{{ $transaction['type'] == 'income' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transaction['type'] == 'income' ? '+' : '-' }}{{ number_format($transaction['amount'], 0, '', ' ') }} FCFA
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Account Balances -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Soldes de Comptes</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Compte</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Solde</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['accountBalances'] as $account)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $account['account'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($account['balance'], 0, '', ' ') }} FCFA</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Actif
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions Rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('accounting.entries.create') ?? '#' }}" class="border border-gray-200 rounded-lg p-4 text-center hover:border-primary-300 hover:shadow-md transition">
                <div class="mx-auto bg-primary-100 p-2 rounded-full w-12 h-12 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Nouvelle Écriture</span>
            </a>
            
            <a href="{{ route('accounting.reports.index') ?? '#' }}" class="border border-gray-200 rounded-lg p-4 text-center hover:border-primary-300 hover:shadow-md transition">
                <div class="mx-auto bg-green-100 p-2 rounded-full w-12 h-12 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Rapports</span>
            </a>
            
            <a href="{{ route('accounting.chart-of-accounts.index') ?? '#' }}" class="border border-gray-200 rounded-lg p-4 text-center hover:border-primary-300 hover:shadow-md transition">
                <div class="mx-auto bg-purple-100 p-2 rounded-full w-12 h-12 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Plan Comptable</span>
            </a>
            
            <a href="{{ route('accounting.journals.index') ?? '#' }}" class="border border-gray-200 rounded-lg p-4 text-center hover:border-primary-300 hover:shadow-md transition">
                <div class="mx-auto bg-yellow-100 p-2 rounded-full w-12 h-12 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Journaux</span>
            </a>
        </div>
    </div>
</div>
@endsection