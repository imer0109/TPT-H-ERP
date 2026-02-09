@extends('layouts.app')

@section('title', 'Grand Livre')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Grand Livre</h1>
        <p class="text-gray-600 mt-2">Consultez les mouvements détaillés de chaque compte</p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex-1">
                    <form method="GET" action="{{ route('accounting.general-ledger') }}" class="flex space-x-4">
                        <div class="flex-1">
                            <label for="account_id" class="block text-sm font-medium text-gray-700 mb-1">Compte</label>
                            <select name="account_id" id="account_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Tous les comptes</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->code }} - {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                                   class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                                   class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(isset($account))
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                {{ $account->code }} - {{ $account->name }}
                <span class="text-sm font-normal ml-2">Solde: {{ number_format($account->current_balance ?? 0, 2, ',', ' ') }} {{ config('app.currency') }}</span>
            </h2>
        </div>
        @endif

        <div class="overflow-x-auto">
            @if(isset($entries) && $entries->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Journal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Libellé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Débit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Crédit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Solde</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $running_balance = 0;
                    @endphp
                    @foreach($entries as $entry)
                        @php
                            if ($entry->debit_account_id == $account->id) {
                                $running_balance += $entry->debit_amount;
                            } else {
                                $running_balance -= $entry->credit_amount;
                            }
                        @endphp
                        <tr class="hover:bg-primary-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry->entry_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry->journal->name ?? '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry->reference }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">{{ $entry->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ $entry->debit_current ? number_format($entry->debit_current, 2, ',', ' ') : '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ $entry->credit_current ? number_format($entry->credit_current, 2, ',', ' ') : '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">{{ number_format($running_balance, 2, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="4" class="px-6 py-3 text-sm text-gray-900 text-right">Total</td>
                        <td class="px-6 py-3 text-sm text-gray-900 text-right">
                            {{ number_format($entries->sum('debit_current'), 2, ',', ' ') }}
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-900 text-right">
                            {{ number_format($entries->sum('credit_current'), 2, ',', ' ') }}
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-900 text-right">
                            {{ number_format($running_balance, 2, ',', ' ') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
            @elseif(isset($accounts) && $accounts->count() > 0)
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($accounts as $account)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h3 class="font-semibold text-gray-800">{{ $account->code }} - {{ $account->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $account->description }}</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-sm">Solde:</span>
                            <span class="font-medium">{{ number_format($account->current_balance ?? 0, 2, ',', ' ') }} {{ config('app.currency') }}</span>
                        </div>
                        <a href="{{ route('accounting.general-ledger', ['account_id' => $account->id, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" 
                           class="mt-3 inline-block px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded hover:bg-primary-200 transition-colors">
                            Voir détails
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="p-6 text-center text-gray-500">
                <p>Aucune donnée disponible</p>
            </div>
            @endif
        </div>

        @if(isset($entries) && $entries->count() > 0)
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    Affichage de {{ $entries->count() }} entrée(s)
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('accounting.export.general-ledger-pdf', request()->all()) }}" 
                       class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Exporter PDF
                    </a>
                    <a href="{{ route('accounting.export.general-ledger-excel', request()->all()) }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Exporter Excel
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection