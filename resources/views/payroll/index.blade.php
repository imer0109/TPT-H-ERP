@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestion de la Paie</h3>
                    <div class="card-tools">
                        <a href="{{ route('payroll.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouvelle Fiche de Paie
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Période</label>
                                <input type="month" class="form-control" id="period-filter">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Statut</label>
                                <select class="form-control" id="status-filter">
                                    <option value="">Tous</option>
                                    <option value="draft">Brouillon</option>
                                    <option value="validated">Validé</option>
                                    <option value="paid">Payé</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Employé</label>
                                <select class="form-control select2" id="employee-filter">
                                    <option value="">Tous</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary" id="filter-btn">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="payroll-table">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Employé</th>
                                    <th>Période</th>
                                    <th>Salaire de Base</th>
                                    <th>Salaire Brut</th>
                                    <th>Salaire Net</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payslips as $payslip)
                                <tr>
                                    <td>{{ $payslip->reference }}</td>
                                    <td>{{ $payslip->employee->full_name }}</td>
                                    <td>{{ $payslip->period->format('F Y') }}</td>
                                    <td>{{ number_format($payslip->base_salary, 2) }} €</td>
                                    <td>{{ number_format($payslip->gross_salary, 2) }} €</td>
                                    <td>{{ number_format($payslip->net_salary, 2) }} €</td>
                                    <td>
                                        <span class="badge badge-{{ $payslip->status_color }}">{{ $payslip->status_label }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('payroll.show', $payslip) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($payslip->status === 'draft')
                                            <a href="{{ route('payroll.edit', $payslip) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success validate-btn" data-id="{{ $payslip->id }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @endif
                                            @if($payslip->status === 'validated')
                                            <button type="button" class="btn btn-sm btn-primary pay-btn" data-id="{{ $payslip->id }}">
                                                <i class="fas fa-money-bill"></i>
                                            </button>
                                            @endif
                                            <a href="{{ route('payroll.pdf', $payslip) }}" class="btn btn-sm btn-secondary" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $payslips->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialisation de Select2
    $('.select2').select2();
    
    // Initialisation de DataTables
    $('#payroll-table').DataTable({
        "paging": false,
        "info": false,
        "order": [[2, 'desc']]
    });
    
    // Gestion des filtres
    $('#filter-btn').click(function() {
        let period = $('#period-filter').val();
        let status = $('#status-filter').val();
        let employee = $('#employee-filter').val();
        
        window.location.href = `{{ route('payroll.index') }}?period=${period}&status=${status}&employee=${employee}`;
    });
    
    // Validation d'une fiche de paie
    $('.validate-btn').click(function() {
        let id = $(this).data('id');
        if (confirm('Êtes-vous sûr de vouloir valider cette fiche de paie ?')) {
            $.post(`{{ url('payroll') }}/${id}/validate`, {
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
    
    // Paiement d'une fiche de paie
    $('.pay-btn').click(function() {
        let id = $(this).data('id');
        if (confirm('Êtes-vous sûr de vouloir marquer cette fiche de paie comme payée ?')) {
            $.post(`{{ url('payroll') }}/${id}/pay`, {
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