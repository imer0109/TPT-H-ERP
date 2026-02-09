@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-6">
    <div class="max-w-7xl mx-auto bg-white shadow-lg rounded-2xl p-8 border border-gray-200">
        
        <!-- En-tête -->
        <div class="flex flex-col md:flex-row justify-between items-center border-b pb-4 mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Gestion des Congés</h3>
            <a href="{{ route('hr.leaves.create') }}"
               class="mt-4 md:mt-0 px-5 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
               Nouvelle Demande
            </a>
        </div>

        <!-- Filtres -->
        <form action="{{ route('hr.leaves.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
            </select>

            <input type="date" name="start_date" value="{{ request('start_date') }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                placeholder="Date de début">

            <input type="date" name="end_date" value="{{ request('end_date') }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                placeholder="Date de fin">

            <div class="flex gap-2">
                <button type="submit" class="w-1/2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    Filtrer
                </button>
                <a href="{{ route('hr.leaves.index') }}"
                   class="w-1/2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                   Réinitialiser
                </a>
            </div>
        </form>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 divide-y divide-gray-200 text-sm">
                <thead class="bg-primary-50 text-gray-700 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left">Employé</th>
                        <th class="px-4 py-3 text-left">Type de Congé</th>
                        <th class="px-4 py-3 text-left">Début</th>
                        <th class="px-4 py-3 text-left">Fin</th>
                        <th class="px-4 py-3 text-center">Durée</th>
                        <th class="px-4 py-3 text-center">Statut</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($leaves as $leave)
                    <tr class="hover:bg-primary-50 transition">
                        <td class="px-4 py-3 text-gray-800">{{ $leave->employee->full_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $leave->leaveType->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $leave->start_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $leave->end_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-center">{{ $leave->duration }} j</td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$leave->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $leave->status_label ?? $leave->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('hr.leaves.show', $leave) }}"
                                   class="px-3 py-1 bg-primary-500 text-white rounded-md hover:bg-primary-600 text-xs transition">
                                   Voir
                                </a>

                                @if($leave->status === 'pending')
                                    @can('approve-leaves')
                                    <button type="button" onclick="approveLeave('{{ $leave->id }}')"
                                            class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 text-xs transition">
                                            Approuver
                                    </button>
                                    <button type="button" onclick="rejectLeave('{{ $leave->id }}')"
                                            class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-xs transition">
                                            Rejeter
                                    </button>
                                    @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500">Aucune demande trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $leaves->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function approveLeave(leaveId) {
    if (confirm('Êtes-vous sûr de vouloir approuver cette demande de congé ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/leaves/${leaveId}/approve`;
        form.innerHTML = `@csrf`;
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectLeave(leaveId) {
    const reason = prompt('Veuillez indiquer la raison du rejet :');
    if (reason !== null) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/leaves/${leaveId}/reject`;
        form.innerHTML = `@csrf
            <input type="hidden" name="rejection_reason" value="${reason}">`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
