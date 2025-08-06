@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestion des Congés</h3>
                    <div class="card-tools">
                        <a href="{{ route('leaves.create') }}" class="btn btn-primary">Nouvelle Demande</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <form action="{{ route('leaves.index') }}" method="GET" class="row">
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">Tous les statuts</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="Date de début">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="Date de fin">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filtrer</button>
                                <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Réinitialiser</a>
                            </div>
                        </form>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Type de Congé</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Durée</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $leave)
                            <tr>
                                <td>{{ $leave->employee->full_name }}</td>
                                <td>{{ $leave->leave_type->name }}</td>
                                <td>{{ $leave->start_date->format('d/m/Y') }}</td>
                                <td>{{ $leave->end_date->format('d/m/Y') }}</td>
                                <td>{{ $leave->duration }} jours</td>
                                <td>
                                    <span class="badge badge-{{ $leave->status_color }}">
                                        {{ $leave->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-info">Voir</a>
                                        @if($leave->status === 'pending')
                                            @can('approve-leaves')
                                            <button type="button" class="btn btn-sm btn-success" onclick="approveLeave('{{ $leave->id }}')">Approuver</button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="rejectLeave('{{ $leave->id }}')">Rejeter</button>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $leaves->links() }}
                    </div>
                </div>
            </div>
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