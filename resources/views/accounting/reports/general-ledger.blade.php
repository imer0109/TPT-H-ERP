@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-10">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Grand Livre</h2>

            <div class="flex gap-3">
                <a href="{{ route('accounting.export.general-ledger-pdf', request()->all()) }}"
                   class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('accounting.export.excel', request()->all()) }}"
                   class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" class="bg-white shadow rounded-2xl p-6 mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Date début -->
                <div>
                    <label for="date_start" class="block text-sm font-semibold text-gray-700 mb-1">Date début</label>
                    <input type="date" id="date_start" name="date_start"
                           value="{{ request('date_start') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-400 focus:outline-none" />
                </div>

                <!-- Date fin -->
                <div>
                    <label for="date_end" class="block text-sm font-semibold text-gray-700 mb-1">Date fin</label>
                    <input type="date" id="date_end" name="date_end"
                           value="{{ request('date_end') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-400 focus:outline-none" />
                </div>

                <!-- Compte -->
                <div>
                    <label for="account_id" class="block text-sm font-semibold text-gray-700 mb-1">Compte</label>
                    <select id="account_id" name="account_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-primary-400 focus:outline-none">
                        <option value="">Tous les comptes</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->code }} - {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end gap-3">
                    <button type="submit"
                            class="w-full sm:w-auto bg-primary-500 hover:bg-primary-600 text-white font-medium px-5 py-2 rounded-lg shadow transition">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <a href="{{ route('accounting.reports.general-ledger') }}"
                       class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-5 py-2 rounded-lg shadow transition">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>
            </div>
        </form>

        <!-- Résultats -->
        <div class="bg-white shadow rounded-2xl p-6 overflow-x-auto">
            @if($entries->count() > 0)
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-sm uppercase">
                            <th class="text-left p-3 border-b">Date</th>
                            <th class="text-left p-3 border-b">Journal</th>
                            <th class="text-left p-3 border-b">N° Pièce</th>
                            <th class="text-left p-3 border-b">Compte</th>
                            <th class="text-left p-3 border-b">Description</th>
                            <th class="text-left p-3 border-b">Référence</th>
                            <th class="text-right p-3 border-b">Débit</th>
                            <th class="text-right p-3 border-b">Crédit</th>
                            <th class="text-right p-3 border-b">Solde</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $runningBalance = 0; @endphp
                        @foreach($entries as $entry)
                            @php $runningBalance += $entry->debit_amount - $entry->credit_amount; @endphp
                            <tr class="hover:bg-primary-50 transition">
                                <td class="p-3 border-b">{{ $entry->entry_date->format('d/m/Y') }}</td>
                                <td class="p-3 border-b">{{ $entry->journal->code }}</td>
                                <td class="p-3 border-b">{{ $entry->entry_number }}</td>
                                <td class="p-3 border-b">
                                    {{ $entry->debitAccount->code ?? $entry->creditAccount->code }} -
                                    {{ $entry->debitAccount->name ?? $entry->creditAccount->name }}
                                </td>
                                <td class="p-3 border-b">{{ $entry->description }}</td>
                                <td class="p-3 border-b">{{ $entry->reference_number }}</td>
                                <td class="text-right p-3 border-b">
                                    {{ $entry->debit_amount > 0 ? number_format($entry->debit_amount, 2) : '' }}
                                </td>
                                <td class="text-right p-3 border-b">
                                    {{ $entry->credit_amount > 0 ? number_format($entry->credit_amount, 2) : '' }}
                                </td>
                                <td class="text-right p-3 border-b font-semibold {{ $runningBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($runningBalance, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="bg-gray-100 font-semibold">
                            <td colspan="6" class="p-3 border-t">TOTAUX</td>
                            <td class="text-right p-3 border-t">{{ number_format($entries->sum('debit_amount'), 2) }}</td>
                            <td class="text-right p-3 border-t">{{ number_format($entries->sum('credit_amount'), 2) }}</td>
                            <td class="text-right p-3 border-t {{ $runningBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($runningBalance, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    {{ $entries->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-10 text-gray-600">
                    <i class="fas fa-info-circle text-3xl text-primary-500 mb-2"></i>
                    <p>Aucune écriture trouvée avec les critères sélectionnés.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('select[name="account_id"]').addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endpush
@endsection
