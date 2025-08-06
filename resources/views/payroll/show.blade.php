@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails de la Fiche de Paie</h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <a href="{{ route('payroll.pdf', $payslip) }}" class="btn btn-primary" target="_blank">
                                <i class="fas fa-file-pdf"></i> Télécharger PDF
                            </a>
                            @if($payslip->status === 'draft')
                            <a href="{{ route('payroll.edit', $payslip) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <button type="button" class="btn btn-success" id="validate-btn">
                                <i class="fas fa-check"></i> Valider
                            </button>
                            @endif
                            @if($payslip->status === 'validated')
                            <button type="button" class="btn btn-primary" id="pay-btn">
                                <i class="fas fa-money-bill"></i> Marquer comme Payé
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Informations Générales</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Référence</th>
                                    <td>{{ $payslip->reference }}</td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        <span class="badge badge-{{ $payslip->status_color }}">{{ $payslip->status_label }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Période</th>
                                    <td>{{ $payslip->period->format('F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Date de Génération</th>
                                    <td>{{ $payslip->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @if($payslip->validated_at)
                                <tr>
                                    <th>Date de Validation</th>
                                    <td>{{ $payslip->validated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                                @if($payslip->paid_at)
                                <tr>
                                    <th>Date de Paiement</th>
                                    <td>{{ $payslip->paid_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Informations Employé</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Nom Complet</th>
                                    <td>{{ $payslip->employee->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Matricule</th>
                                    <td>{{ $payslip->employee->employee_id }}</td>
                                </tr>
                                <tr>
                                    <th>Poste</th>
                                    <td>{{ $payslip->employee->position->title }}</td>
                                </tr>
                                <tr>
                                    <th>Département</th>
                                    <td>{{ $payslip->employee->position->department }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h4>Détails du Salaire</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Salaire de Base</th>
                                        <td>{{ number_format($payslip->base_salary, 2) }} €</td>
                                    </tr>
                                    <tr>
                                        <th>Jours Travaillés</th>
                                        <td>{{ $payslip->worked_days }} jours</td>
                                    </tr>
                                    <tr>
                                        <th>Heures Supplémentaires</th>
                                        <td>{{ $payslip->overtime_hours }} heures</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h4>Gains</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-right">Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payslip->earnings as $item)
                                        <tr>
                                            <td>{{ $item['name'] }}</td>
                                            <td class="text-right">{{ number_format($item['amount'], 2) }} €</td>
                                        </tr>
                                        @endforeach
                                        <tr class="font-weight-bold">
                                            <td>Total des Gains</td>
                                            <td class="text-right">{{ number_format($payslip->gross_salary, 2) }} €</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Déductions</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-right">Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payslip->deductions as $item)
                                        <tr>
                                            <td>{{ $item['name'] }}</td>
                                            <td class="text-right">{{ number_format($item['amount'], 2) }} €</td>
                                        </tr>
                                        @endforeach
                                        <tr class="font-weight-bold">
                                            <td>Total des Déductions</td>
                                            <td class="text-right">{{ number_format($payslip->gross_salary - $payslip->net_salary, 2) }} €</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr class="bg-light font-weight-bold">
                                        <th width="30%">Salaire Brut</th>
                                        <td class="text-right">{{ number_format($payslip->gross_salary, 2) }} €</td>
                                    </tr>
                                    <tr class="bg-light font-weight-bold">
                                        <th>Total Déductions</th>
                                        <td class="text-right">{{ number_format($payslip->gross_salary - $payslip->net_salary, 2) }} €</td>
                                    </tr>
                                    <tr class="bg-success text-white font-weight-bold">
                                        <th>Salaire Net à Payer</th>
                                        <td class="text-right">{{ number_format($payslip->net_salary, 2) }} €</td>
                                    </tr>
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
$(document).ready(function() {
    // Validation de la fiche de paie
    $('#validate-btn').click(function() {
        if (confirm('Êtes-vous sûr de vouloir valider cette fiche de paie ?')) {
            $.post('{{ route("payroll.validate", $payslip) }}', {
                _token: '{{ csrf_token() }}'
            })
            .done(function() {
                window.location.reload();
            })
            .fail(function(response) {
                alert('Erreur lors de la validation : ' + response.responseJSON.message);
            });
        }
    });
    
    // Paiement de la fiche de paie
    $('#pay-btn').click(function() {
        if (confirm('Êtes-vous sûr de vouloir marquer cette fiche de paie comme payée ?')) {
            $.post('{{ route("payroll.pay", $payslip) }}', {
                _token: '{{ csrf_token() }}'
            })
            .done(function() {
                window.location.reload();
            })
            .fail(function(response) {
                alert('Erreur lors du paiement : ' + response.responseJSON.message);
            });
        }
    });
});
</script>
@endpush
@endsection