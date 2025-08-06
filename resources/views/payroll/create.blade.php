@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nouvelle Fiche de Paie</h3>
                    <div class="card-tools">
                        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <form action="{{ route('payroll.store') }}" method="POST" id="payroll-form">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id">Employé <span class="text-danger">*</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control select2 @error('employee_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un employé</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" data-base-salary="{{ $employee->contract->base_salary }}">
                                                {{ $employee->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="period">Période <span class="text-danger">*</span></label>
                                    <input type="month" name="period" id="period" class="form-control @error('period') is-invalid @enderror" required>
                                    @error('period')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="base_salary">Salaire de Base</label>
                                    <input type="number" step="0.01" name="base_salary" id="base_salary" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="worked_days">Jours Travaillés</label>
                                    <input type="number" name="worked_days" id="worked_days" class="form-control" value="22">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="overtime_hours">Heures Supplémentaires</label>
                                    <input type="number" step="0.5" name="overtime_hours" id="overtime_hours" class="form-control" value="0">
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
                                        <input type="number" step="0.01" name="earnings[{{ $item->id }}]" class="form-control earning-input" data-calculation="{{ $item->calculation_type }}" value="0">
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
                                        <input type="number" step="0.01" name="deductions[{{ $item->id }}]" class="form-control deduction-input" data-calculation="{{ $item->calculation_type }}" value="0">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Salaire Brut</label>
                                    <input type="number" step="0.01" id="gross_salary" name="gross_salary" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Total Déductions</label>
                                    <input type="number" step="0.01" id="total_deductions" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Salaire Net</label>
                                    <input type="number" step="0.01" id="net_salary" name="net_salary" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
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
    // Initialisation de Select2
    $('.select2').select2();
    
    // Mise à jour du salaire de base lors de la sélection d'un employé
    $('#employee_id').change(function() {
        let baseSalary = $(this).find(':selected').data('base-salary') || 0;
        $('#base_salary').val(baseSalary);
        calculateSalary();
    });
    
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
        let employee = $('#employee_id').val();
        let period = $('#period').val();
        
        if (!employee || !period) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
            return;
        }
        
        if (!confirm('Êtes-vous sûr de vouloir créer cette fiche de paie ?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection