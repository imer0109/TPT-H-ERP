@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-6">
    <div class="max-w-7xl mx-auto bg-white shadow-xl rounded-2xl p-8">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-2xl font-bold text-indigo-600">Journal Comptable</h3>
            <a href="{{ route('accounting.export.excel', request()->all()) }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
               <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
        </div>

        <!-- Filters -->
        <form method="GET" class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Journal</label>
                <select name="journal_id" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">Tous les journaux</option>
                    @foreach($journals as $journal)
                        <option value="{{ $journal->id }}" {{ request('journal_id') == $journal->id ? 'selected' : '' }}>
                            {{ $journal->code }} - {{ $journal->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Date début</label>
                <input type="date" name="date_start" value="{{ request('date_start') }}"
                       class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Date fin</label>
                <input type="date" name="date_end" value="{{ request('date_end') }}"
                       class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition flex items-center">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="{{ route('accounting.reports.journal') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2.5 rounded-lg font-medium transition flex items-center">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>

        <!-- Results -->
        @if($entries->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-gray-200">
                    <thead class="bg-indigo-100 text-indigo-800 font-semibold">
                        <tr>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Journal</th>
                            <th class="px-4 py-2">N° Pièce</th>
                            <th class="px-4 py-2">Compte Débit</th>
                            <th class="px-4 py-2">Compte Crédit</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2 text-right">Débit</th>
                            <th class="px-4 py-2 text-right">Crédit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($entries as $entry)
                            <tr>
                                <td class="px-4 py-2">{{ $entry->entry_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $entry->journal->code }}</td>
                                <td class="px-4 py-2">{{ $entry->entry_number }}</td>
                                <td class="px-4 py-2">{{ $entry->debitAccount->code }} - {{ $entry->debitAccount->name }}</td>
                                <td class="px-4 py-2">{{ $entry->creditAccount->code }} - {{ $entry->creditAccount->name }}</td>
                                <td class="px-4 py-2">{{ $entry->description }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($entry->debit_amount, 2) }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($entry->credit_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-semibold">
                        <tr>
                            <td colspan="6" class="px-4 py-2">TOTAUX</td>
                            <td class="px-4 py-2 text-right">{{ number_format($entries->sum('debit_amount'), 2) }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($entries->sum('credit_amount'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4 flex justify-center">
                {{ $entries->withQueryString()->links() }}
            </div>
        @else
            <div class="bg-primary-50 text-primary-800 p-4 rounded-lg text-center">
                <i class="fas fa-info-circle mr-2"></i> Aucune écriture trouvée avec les critères sélectionnés.
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('select[name="journal_id"]').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush
