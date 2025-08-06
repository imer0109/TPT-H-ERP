@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails du Contrat</h3>
                    <div class="card-tools">
                        <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning">Modifier</a>
                        <a href="{{ route('contracts.index') }}" class="btn btn-secondary">Retour</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Employé</th>
                                    <td>{{ $contract->employee->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Type de Contrat</th>
                                    <td>{{ $contract->contract_type }}</td>
                                </tr>
                                <tr>
                                    <th>Date de Début</th>
                                    <td>{{ $contract->start_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Date de Fin</th>
                                    <td>{{ $contract->end_date ? $contract->end_date->format('d/m/Y') : 'Indéterminé' }}</td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        <span class="badge badge-{{ $contract->isActive() ? 'success' : 'danger' }}">
                                            {{ $contract->isActive() ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Salaire de Base</th>
                                    <td>{{ number_format($contract->base_salary, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                <tr>
                                    <th>Période d'Essai</th>
                                    <td>{{ $contract->trial_period }} mois</td>
                                </tr>
                                <tr>
                                    <th>Avantages</th>
                                    <td>{{ $contract->benefits ?: 'Aucun avantage spécifié' }}</td>
                                </tr>
                                <tr>
                                    <th>Document du Contrat</th>
                                    <td>
                                        @if($contract->contract_file)
                                            <a href="{{ Storage::url($contract->contract_file) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-download"></i> Télécharger
                                            </a>
                                        @else
                                            Aucun document
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection