@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestion des Présences</h3>
                    <div class="card-tools">
                        <a href="{{ route('attendances.create') }}" class="btn btn-primary">Nouveau Pointage</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <form action="{{ route('attendances.index') }}" method="GET" class="row">
                            <div class="col-md-3">
                                <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <select name="department" class="form-control">
                                    <option value="">Tous les départements</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">Tous les statuts</option>
                                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Présent</option>
                                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>En retard</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filtrer</button>
                                <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Réinitialiser</a>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Employé</th>
                                    <th>Département</th>
                                    <th>Date</th>
                                    <th>Heure d'Arrivée</th>
                                    <th>Heure de Départ</th>
                                    <th>Statut</th>
                                    <th>Retard (min)</th>
                                    <th>Heures Supp.</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->employee->full_name }}</td>
                                    <td>{{ $attendance->employee->department->name }}</td>
                                    <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                    <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}</td>
                                    <td>{{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $attendance->status_color }}">
                                            {{ $attendance->status_label }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->late_minutes ?: '-' }}</td>
                                    <td>{{ $attendance->overtime_hours ?: '-' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-sm btn-info">Détails</a>
                                            @if(!$attendance->check_out)
                                                <button type="button" class="btn btn-sm btn-success" onclick="checkOut('{{ $attendance->id }}')">Pointer Sortie</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
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