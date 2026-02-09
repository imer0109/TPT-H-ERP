@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto bg-white shadow-lg rounded-2xl overflow-hidden">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center bg-gradient-to-r from-primary-600 to-indigo-600 p-6">
            <h3 class="text-xl font-semibold text-white">Gestion des Présences</h3>
            <a href="{{ route('hr.attendances.create') }}" 
               class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-white text-primary-700 font-semibold rounded-lg shadow hover:bg-primary-100 transition">
                Nouveau Pointage
            </a>
        </div>

        <!-- Filtres -->
        <div class="p-6 border-b border-gray-200">
            <form action="{{ route('hr.attendances.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="date" 
                           value="{{ request('date', date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Poste</label>
                    <select name="position" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <option value="">Tous les postes</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('position') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <option value="">Tous les statuts</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Présent</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>En retard</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" 
                            class="w-full md:w-auto px-4 py-2 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition">
                        Filtrer
                    </button>
                    <a href="{{ route('hr.attendances.index') }}" 
                       class="w-full md:w-auto px-4 py-2 bg-gray-300 text-gray-800 font-semibold rounded-lg hover:bg-gray-400 transition">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full border-t border-gray-200">
                <thead class="bg-primary-50">
                    <tr class="text-left text-sm font-semibold text-gray-700">
                        <th class="px-6 py-3">Employé</th>
                        <th class="px-6 py-3">Poste</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Heure d'Arrivée</th>
                        <th class="px-6 py-3">Heure de Départ</th>
                        <th class="px-6 py-3">Statut</th>
                        <th class="px-6 py-3">Retard (min)</th>
                        <th class="px-6 py-3">Heures Supp.</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @foreach($attendances as $attendance)
                    <tr class="hover:bg-primary-50">
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $attendance->employee->full_name }}</td>
                        <td class="px-6 py-3">{{ $attendance->employee->currentPosition->title ?? 'Non défini' }}</td>
                        <td class="px-6 py-3">{{ $attendance->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-3">{{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}</td>
                        <td class="px-6 py-3">{{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}</td>
                        <td class="px-6 py-3">
                            @php
                                $statusColors = [
                                    'present' => 'bg-green-100 text-green-700',
                                    'absent' => 'bg-red-100 text-red-700',
                                    'late' => 'bg-yellow-100 text-yellow-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$attendance->status_label] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($attendance->status_label) }}
                            </span>
                        </td>
                        <td class="px-6 py-3">{{ $attendance->late_minutes ?: '-' }}</td>
                        <td class="px-6 py-3">{{ $attendance->overtime_hours ?: '-' }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('hr.attendances.show', $attendance) }}" 
                                   class="px-3 py-1 text-sm text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition">
                                   Détails
                                </a>
                                @if(!$attendance->check_out)
                                <button type="button" 
                                        onclick="checkOut('{{ $attendance->id }}')" 
                                        class="px-3 py-1 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                                    Pointer Sortie
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-gray-200">
            {{ $attendances->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function checkOut(attendanceId) {
    if (confirm('Confirmer le pointage de sortie ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/attendances/${attendanceId}/check-out`;
        form.innerHTML = `@csrf`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
