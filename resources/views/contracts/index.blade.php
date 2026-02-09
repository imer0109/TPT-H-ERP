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
                    <!-- Filters -->
                    <form action="{{ route('contracts.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="employee_id">Employé</label>
                                    <select name="employee_id" id="employee_id" class="form-control">
                                        <option value="">Tous les employés</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Statut</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Tous les statuts</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Résilié</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type">Type de Contrat</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Tous les types</option>
                                        <option value="CDI" {{ request('type') == 'CDI' ? 'selected' : '' }}>CDI</option>
                                        <option value="CDD" {{ request('type') == 'CDD' ? 'selected' : '' }}>CDD</option>
                                        <option value="Stage" {{ request('type') == 'Stage' ? 'selected' : '' }}>Stage</option>
                                        <option value="Prestation" {{ request('type') == 'Prestation' ? 'selected' : '' }}>Prestation</option>
                                        <option value="Intérim" {{ request('type') == 'Intérim' ? 'selected' : '' }}>Intérim</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Filtrer</button>
                                <a href="{{ route('contracts.index') }}" class="btn btn-secondary">Réinitialiser</a>
                            </div>
                        </div>
                    </form>
                    
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
                            @forelse($contracts as $contract)
                            <tr>
                                <td>{{ $contract->employee->full_name }}</td>
                                <td>{{ $contract->type }}</td>
                                <td>{{ $contract->start_date->format('d/m/Y') }}</td>
                                <td>{{ $contract->end_date ? $contract->end_date->format('d/m/Y') : 'Indéterminé' }}</td>
                                <td>{{ number_format($contract->base_salary, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    <span class="badge {{ $contract->getStatusBadgeClass() }}">
                                        {{ $contract->getStatusText() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('contracts.show', $contract) }}" class="btn btn-sm btn-info">Voir</a>
                                        <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-sm btn-warning">Modifier</a>
                                        <form action="{{ route('contracts.destroy', $contract) }}" method="POST" class="d-inline" id="delete-form-{{ $contract->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $contract->id }}')">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucun contrat trouvé</td>
                            </tr>
                            @endforelse
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