@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Modifier la Fiche de Paie</h3>
                    <div class="card-tools">
                        <a href="{{ route('payroll.show', $payslip) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <form action="{{ route('payroll.update', $payslip) }}" method="POST" id="payroll-form">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Employé</label>
                                    <input type="text" class="form-control" value="{{ $payslip->employee->full_name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Période</label>
                                    <input type="month" class="form-control" value="{{ $payslip->period->format('Y-m') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="base_salary">Salaire de Base</label>
                                    <input type="number" step="0.01" name="base_salary" id="base_salary" class="form-control" value="{{ $payslip->base_salary }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="worked_days">Jours Travaillés</label>
                                    <input type="number" name="worked_days" id="worked_days" class="form-control" value="{{ $payslip->worked_days }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="overtime_hours">Heures Supplémentaires</label>
                                    <input type="number" step="0.5" name="overtime_hours" id="overtime_hours" class="form-control" value="{{ $payslip->overtime_hours }}">
                                </div>
                            </div>
                        </div>

                        <h4 class="mt-4">Éléments de Paie</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Gains</h5>
                                <div id="earnings-container">
                                    @foreach($payrollItems->where('type', 'earning') as $item)
                                    <div class="form-group">
                                        <label>{{ $item->name }}</label>
                                        <input type="number" step="0.01" name="earnings[{{ $item->id }}]" 
                                               class="form-control earning-input" 
                                               data-calculation="{{ $item->calculation_type }}" 
                                               value="{{ $payslip->getEarningAmount($item->id) }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Déductions</h5>
                                <div id="deductions-container">
                                    @foreach($payrollItems->where('type', 'deduction') as $item)
                                    <div class="form-group">
                                        <label>{{ $item->name }}</label>
                                        <input type="number" step="0.01" name="deductions[{{ $item->id }}]" 
                                               class="form-control deduction-input" 
                                               data-calculation="{{ $item->calculation_type }}" 
                                               value="{{ $payslip->getDeductionAmount($item->id) }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Salaire Brut</label>
                                    <input type="number" step="0.01" id="gross_salary" name="gross_salary" class="form-control" value="{{ $payslip->gross_salary }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Total Déductions</label>
                                    <input type="number" step="0.01" id="total_deductions" class="form-control" value="{{ $payslip->gross_salary - $payslip->net_salary }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Salaire Net</label>
                                    <input type="number" step="0.01" id="net_salary" name="net_salary" class="form-control" value="{{ $payslip->net_salary }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les Modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Calcul automatique des salaires
    function calculateSalary() {
        let baseSalary = parseFloat($('#base_salary').val()) || 0;
        let workedDays = parseInt($('#worked_days').val()) || 0;
        let overtimeHours = parseFloat($('#overtime_hours').val()) || 0;
        
        // Calcul du salaire de base au prorata des jours travaillés
        let prorataSalary = (baseSalary / 22) * workedDays;
        
        // Calcul des heures supplémentaires (majoration de 25%)
        let overtimePay = (baseSalary / 151.67) * overtimeHours * 1.25;
        
        // Calcul des gains
        let totalEarnings = prorataSalary + overtimePay;
        $('.earning-input').each(function() {
            let value = parseFloat($(this).val()) || 0;
            let calculationType = $(this).data('calculation');
            
            if (calculationType === 'percentage') {
                value = (baseSalary * value) / 100;
            }
            
            totalEarnings += value;
        });
        
        // Calcul des déductions
        let totalDeductions = 0;
        $('.deduction-input').each(function() {
            let value = parseFloat($(this).val()) || 0;
            let calculationType = $(this).data('calculation');
            
            if (calculationType === 'percentage') {
                value = (totalEarnings * value) / 100;
            }
            
            totalDeductions += value;
        });
        
        // Mise à jour des totaux
        $('#gross_salary').val(totalEarnings.toFixed(2));
        $('#total_deductions').val(totalDeductions.toFixed(2));
        $('#net_salary').val((totalEarnings - totalDeductions).toFixed(2));
    }
    
    // Recalcul lors de la modification des valeurs
    $('#worked_days, #overtime_hours, .earning-input, .deduction-input').on('input', calculateSalary);
    
    // Validation du formulaire
    $('#payroll-form').submit(function(e) {
        if (!confirm('Êtes-vous sûr de vouloir modifier cette fiche de paie ?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection