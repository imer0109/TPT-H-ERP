@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails de la Demande de Congé</h3>
                    <div class="card-tools">
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Retour</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Employé</th>
                                    <td>{{ $leave->employee->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Type de Congé</th>
                                    <td>{{ $leave->leave_type->name }}</td>
                                </tr>
                                <tr>
                                    <th>Date de Début</th>
                                    <td>{{ $leave->start_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Date de Fin</th>
                                    <td>{{ $leave->end_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Durée</th>
                                    <td>{{ $leave->duration }} jours</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        <span class="badge badge-{{ $leave->status_color }}">
                                            {{ $leave->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Motif</th>
                                    <td>{{ $leave->reason }}</td>
                                </tr>
                                @if($leave->supporting_document)
                                <tr>
                                    <th>Document Justificatif</th>
                                    <td>
                                        <a href="{{ Storage::url($leave->supporting_document) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                @if($leave->status === 'approved')
                                <tr>
                                    <th>Approuvé par</th>
                                    <td>{{ $leave->approved_by->name }} le {{ $leave->approved_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @elseif($leave->status === 'rejected')
                                <tr>
                                    <th>Rejeté par</th>
                                    <td>{{ $leave->rejected_by->name }} le {{ $leave->rejected_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Motif du Rejet</th>
                                    <td>{{ $leave->rejection_reason }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($leave->status === 'pending' && auth()->user()->can('approve-leaves'))
                    <div class="mt-4">
                        <button type="button" class="btn btn-success" onclick="approveLeave('{{ $leave->id }}')">Approuver</button>
                        <button type="button" class="btn btn-danger" onclick="rejectLeave('{{ $leave->id }}')">Rejeter</button>
                    </div>
                    @endif
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