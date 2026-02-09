@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-6">
    <div class="max-w-7xl mx-auto bg-white shadow-xl rounded-2xl p-8">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-2xl font-bold text-indigo-600">Balance Générale</h3>
            <div class="flex gap-2">
                <a href="{{ route('accounting.export.trial-balance-pdf', request()->all()) }}" 
                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </a>
                <a href="{{ route('accounting.export.excel', request()->all()) }}" 
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </a>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Date de fin</label>
                    <input type="date" name="date_end" 
                           value="{{ request('date_end', $date_end) }}"
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Société</label>
                    <select name="company_id" 
                            class="w-full border border-gray-300 rounded-lg p-2.5 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">Toutes les sociétés</option>
                        @foreach(App\Models\Company::all() as $company)
                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition flex items-center">
                        <i class="fas fa-search mr-2"></i> Filtrer
                    </button>
                    <a href="{{ route('accounting.reports.trial-balance') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2.5 rounded-lg font-medium transition flex items-center">
                        <i class="fas fa-times mr-2"></i> Réinitialiser
                    </a>
                </div>
            </div>
        </form>

        <!-- Results -->
        @if(count($balances) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-gray-200">
                    <thead class="bg-indigo-100 text-indigo-800">
                        <tr>
                            <th class="px-4 py-3 border-b">Code Compte</th>
                            <th class="px-4 py-3 border-b">Nom du Compte</th>
                            <th class="px-4 py-3 border-b text-right">Débit</th>
                            <th class="px-4 py-3 border-b text-right">Crédit</th>
                            <th class="px-4 py-3 border-b text-right">Solde Débiteur</th>
                            <th class="px-4 py-3 border-b text-right">Solde Créditeur</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php 
                            $totalDebit = 0;
                            $totalCredit = 0;
                            $totalDebitBalance = 0;
                            $totalCreditBalance = 0;
                        @endphp
                        @foreach($balances as $balance)
                            @php
                                $totalDebit += $balance['debit'];
                                $totalCredit += $balance['credit'];
                                if ($balance['balance'] > 0) {
                                    $totalDebitBalance += $balance['balance'];
                                } else {
                                    $totalCreditBalance += abs($balance['balance']);
                                }
                            @endphp
                            <tr class="hover:bg-primary-50">
                                <td class="px-4 py-2">{{ $balance['account']->code }}</td>
                                <td class="px-4 py-2">{{ $balance['account']->name }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($balance['debit'], 2) }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($balance['credit'], 2) }}</td>
                                <td class="px-4 py-2 text-right text-green-600">
                                    {{ $balance['balance'] > 0 ? number_format($balance['balance'], 2) : '' }}
                                </td>
                                <td class="px-4 py-2 text-right text-red-600">
                                    {{ $balance['balance'] < 0 ? number_format(abs($balance['balance']), 2) : '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-indigo-50 font-semibold">
                        <tr>
                            <td colspan="2" class="px-4 py-3">TOTAUX</td>
                            <td class="px-4 py-3 text-right">{{ number_format($totalDebit, 2) }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($totalCredit, 2) }}</td>
                            <td class="px-4 py-3 text-right text-green-600">{{ number_format($totalDebitBalance, 2) }}</td>
                            <td class="px-4 py-3 text-right text-red-600">{{ number_format($totalCreditBalance, 2) }}</td>
                        </tr>
                        <tr class="border-t border-gray-200">
                            <td colspan="4" class="px-4 py-3 font-semibold">DIFFÉRENCE (Débit - Crédit)</td>
                            <td colspan="2" class="px-4 py-3 text-right {{ ($totalDebit - $totalCredit) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($totalDebit - $totalCredit, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
                <div class="bg-indigo-600 text-white rounded-xl p-5 text-center shadow-md">
                    <h5 class="text-sm font-medium">Total Débits</h5>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format($totalDebit, 2) }}</h3>
                </div>
                <div class="bg-green-600 text-white rounded-xl p-5 text-center shadow-md">
                    <h5 class="text-sm font-medium">Total Crédits</h5>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format($totalCredit, 2) }}</h3>
                </div>
                <div class="bg-primary-500 text-white rounded-xl p-5 text-center shadow-md">
                    <h5 class="text-sm font-medium">Soldes Débiteurs</h5>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format($totalDebitBalance, 2) }}</h3>
                </div>
                <div class="bg-yellow-500 text-white rounded-xl p-5 text-center shadow-md">
                    <h5 class="text-sm font-medium">Soldes Créditeurs</h5>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format($totalCreditBalance, 2) }}</h3>
                </div>
            </div>
        @else
            <div class="mt-6 bg-primary-50 border border-primary-200 text-primary-700 rounded-lg p-4 flex items-center gap-2">
                <i class="fas fa-info-circle"></i>
                <span>Aucun solde trouvé avec les critères sélectionnés.</span>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('select[name="company_id"]').addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endpush
