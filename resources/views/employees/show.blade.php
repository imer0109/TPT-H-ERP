@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" 
                             src="{{ $employee->photo ? asset('storage/' . $employee->photo) : asset('img/default-avatar.png') }}" 
                             alt="Photo de l'employé">
                    </div>
                    <h3 class="profile-username text-center">{{ $employee->nom }} {{ $employee->prenom }}</h3>
                    <p class="text-muted text-center">{{ $employee->position->title }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Matricule</b> <a class="float-right">{{ $employee->matricule }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $employee->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Téléphone</b> <a class="float-right">{{ $employee->telephone }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Statut</b> 
                            <span class="float-right badge badge-{{ $employee->status === 'actif' ? 'success' : 'danger' }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </li>
                    </ul>
                    <div class="btn-group w-100">
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ $employee->id }}')">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#details" data-toggle="tab">Détails</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contracts" data-toggle="tab">Contrats</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#leaves" data-toggle="tab">Congés</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#attendances" data-toggle="tab">Présences</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#evaluations" data-toggle="tab">Évaluations</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="details">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Informations Personnelles</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Date de Naissance</th>
                                            <td>{{ $employee->date_naissance->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Lieu de Naissance</th>
                                            <td>{{ $employee->lieu_naissance }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nationalité</th>
                                            <td>{{ $employee->nationalite }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Informations Professionnelles</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Département</th>
                                            <td>{{ $employee->position->department }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date d'Embauche</th>
                                            <td>{{ $employee->date_embauche->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Superviseur</th>
                                            <td>
                                                @if($employee->supervisor)
                                                    {{ $employee->supervisor->nom }} {{ $employee->supervisor->prenom }}
                                                @else
                                                    Non assigné
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="contracts">
                            <div class="mb-3">
                                <a href="{{ route('contracts.create', ['employee' => $employee->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Nouveau Contrat
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Date Début</th>
                                            <th>Date Fin</th>
                                            <th>Salaire Base</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->contracts as $contract)
                                        <tr>
                                            <td>{{ $contract->type }}</td>
                                            <td>{{ $contract->date_debut->format('d/m/Y') }}</td>
                                            <td>{{ $contract->date_fin ? $contract->date_fin->format('d/m/Y') : 'Indéterminé' }}</td>
                                            <td>{{ number_format($contract->salaire_base, 2, ',', ' ') }} FCFA</td>
                                            <td>
                                                <span class="badge badge-{{ $contract->isActive() ? 'success' : 'secondary' }}">
                                                    {{ $contract->isActive() ? 'Actif' : 'Terminé' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('contracts.show', $contract) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="leaves">
                            <div class="mb-3">
                                <a href="{{ route('leaves.create', ['employee' => $employee->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Nouvelle Demande
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Date Début</th>
                                            <th>Date Fin</th>
                                            <th>Durée</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->leaves as $leave)
                                        <tr>
                                            <td>{{ $leave->type->name }}</td>
                                            <td>{{ $leave->date_debut->format('d/m/Y') }}</td>
                                            <td>{{ $leave->date_fin->format('d/m/Y') }}</td>
                                            <td>{{ $leave->duration }} jours</td>
                                            <td>
                                                <span class="badge badge-{{ $leave->status_color }}">
                                                    {{ $leave->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('leaves.show', $leave) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($leave->status === 'en_attente')
                                                    <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="attendances">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Arrivée</th>
                                            <th>Départ</th>
                                            <th>Retard</th>
                                            <th>Heures Supp.</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->attendances->sortByDesc('date') as $attendance)
                                        <tr>
                                            <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                            <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}</td>
                                            <td>{{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}</td>
                                            <td>{{ $attendance->late_minutes }} min</td>
                                            <td>{{ $attendance->overtime_minutes }} min</td>
                                            <td>
                                                <span class="badge badge-{{ $attendance->status_color }}">
                                                    {{ $attendance->status_text }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="evaluations">
                            <div class="mb-3">
                                <a href="{{ route('evaluations.create', ['employee' => $employee->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Nouvelle Évaluation
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Période</th>
                                            <th>Date</th>
                                            <th>Évaluateur</th>
                                            <th>Note</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->evaluations->sortByDesc('date') as $evaluation)
                                        <tr>
                                            <td>{{ $evaluation->period }}</td>
                                            <td>{{ $evaluation->date->format('d/m/Y') }}</td>
                                            <td>{{ $evaluation->evaluator->nom }} {{ $evaluation->evaluator->prenom }}</td>
                                            <td>{{ number_format($evaluation->overall_score, 1) }}/5</td>
                                            <td>
                                                <span class="badge badge-{{ $evaluation->status_color }}">
                                                    {{ $evaluation->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('evaluations.show', $evaluation) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($evaluation->status === 'draft')
                                                    <a href="{{ route('evaluations.edit', $evaluation) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(employeeId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet employé ?')) {
        document.getElementById('delete-form-' + employeeId).submit();
    }
}
</script>
@endpush
@endsection