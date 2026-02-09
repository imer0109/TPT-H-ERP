@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow rounded-lg">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="text-xl font-semibold">Rapport Analytique</h3>
            <a href="{{ route('accounting.export.excel', request()->all()) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
        </div>

        <!-- Filters -->
        <form method="GET" class="px-6 py-4 space-y-4 md:space-y-0 md:flex md:space-x-4">
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Centre de Coût</label>
                <select name="cost_center_id" class="w-full border rounded px-3 py-2">
                    <option value="">Tous les centres de coût</option>
                    @foreach(App\Models\CostCenter::all() as $costCenter)
                        <option value="{{ $costCenter->id }}" {{ request('cost_center_id') == $costCenter->id ? 'selected' : '' }}>
                            {{ $costCenter->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Projet</label>
                <select name="project_id" class="w-full border rounded px-3 py-2">
                    <option value="">Tous les projets</option>
                    @foreach(App\Models\Project::all() as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Date début</label>
                <input type="date" name="date_start" value="{{ request('date_start') }}" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Date fin</label>
                <input type="date" name="date_end" value="{{ request('date_end') }}" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded flex items-center mr-2">
                    <i class="fas fa-search mr-1"></i> Filtrer
                </button>
                <a href="{{ route('accounting.reports.analytical') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded flex items-center">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </div>
        </form>

        <!-- Results -->
        @if($entries->count() > 0)
        <div class="overflow-x-auto px-6 py-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Date</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Journal</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">N° Pièce</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Compte</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Centre de Coût</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Projet</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-700">Débit</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-700">Crédit</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($entries as $entry)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ $entry->entry_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-sm">{{ $entry->journal->code ?? '' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $entry->entry_number }}</td>
                            <td class="px-4 py-2 text-sm">{{ $entry->debitAccount->code ?? $entry->creditAccount->code ?? '' }} - {{ $entry->debitAccount->name ?? $entry->creditAccount->name ?? '' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $entry->costCenter->name ?? '' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $entry->project->name ?? '' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $entry->description }}</td>
                            <td class="px-4 py-2 text-right text-sm">{{ number_format($entry->debit_amount, 2) }}</td>
                            <td class="px-4 py-2 text-right text-sm">{{ number_format($entry->credit_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-semibold">
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-right">TOTAUX</td>
                        <td class="px-4 py-2 text-right">{{ number_format($entries->sum('debit_amount'), 2) }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($entries->sum('credit_amount'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4">
            {{ $entries->withQueryString()->links() }}
        </div>
        @else
            <div class="px-6 py-4">
                <div class="bg-primary-50 border-l-4 border-primary-400 text-primary-700 p-4 rounded">
                    <i class="fas fa-info-circle mr-2"></i> Aucune écriture trouvée avec les critères sélectionnés.
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form on select change
    $('select[name="cost_center_id"], select[name="project_id"]').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush
