@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des Contrats</h3>
                    <div class="card-tools">
                        <a href="{{ route('contracts.create') }}" class="btn btn-primary">Nouveau Contrat</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Type de Contrat</th>
                                <th>Date de Début</th>
                                <th>Date de Fin</th>
                                <th>Salaire de Base</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contracts as $contract)
                            <tr>
                                <td>{{ $contract->employee->full_name }}</td>
                                <td>{{ $contract->contract_type }}</td>
                                <td>{{ $contract->start_date->format('d/m/Y') }}</td>
                                <td>{{ $contract->end_date ? $contract->end_date->format('d/m/Y') : 'Indéterminé' }}</td>
                                <td>{{ number_format($contract->base_salary, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    <span class="badge badge-{{ $contract->isActive() ? 'success' : 'danger' }}">
                                        {{ $contract->isActive() ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('contracts.show', $contract) }}" class="btn btn-sm btn-info">Voir</a>
                                        <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-sm btn-warning">Modifier</a>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $contract->id }}')">Supprimer</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $contracts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(contractId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?')) {
        document.getElementById('delete-form-' + contractId).submit();
    }
}
</script>
@endpush
@endsection