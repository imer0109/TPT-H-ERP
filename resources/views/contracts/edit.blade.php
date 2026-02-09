@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Modifier le Contrat</h3>
                </div>
                <form action="{{ route('contracts.update', $contract) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id">Employé</label>
                                    <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un employé</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id', $contract->employee_id) == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="type">Type de Contrat</label>
                                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="CDI" {{ old('type', $contract->type) == 'CDI' ? 'selected' : '' }}>CDI</option>
                                        <option value="CDD" {{ old('type', $contract->type) == 'CDD' ? 'selected' : '' }}>CDD</option>
                                        <option value="Stage" {{ old('type', $contract->type) == 'Stage' ? 'selected' : '' }}>Stage</option>
                                        <option value="Prestation" {{ old('type', $contract->type) == 'Prestation' ? 'selected' : '' }}>Prestation</option>
                                        <option value="Intérim" {{ old('type', $contract->type) == 'Intérim' ? 'selected' : '' }}>Intérim</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="start_date">Date de Début</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="end_date">Date de Fin (optionnel pour CDI)</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $contract->end_date ? $contract->end_date->format('Y-m-d') : '') }}">
                                    @error('end_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Statut</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status', $contract->status) == 'draft' ? 'selected' : '' }}>Brouillon</option>
                                        <option value="pending" {{ old('status', $contract->status) == 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="active" {{ old('status', $contract->status) == 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="terminated" {{ old('status', $contract->status) == 'terminated' ? 'selected' : '' }}>Résilié</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="base_salary">Salaire de Base</label>
                                    <input type="number" step="0.01" name="base_salary" id="base_salary" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary', $contract->base_salary) }}" required>
                                    @error('base_salary')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="trial_period_months">Période d'Essai (en mois)</label>
                                    <input type="number" name="trial_period_months" id="trial_period_months" class="form-control @error('trial_period_months') is-invalid @enderror" value="{{ old('trial_period_months') }}">
                                    @error('trial_period_months')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="trial_period_start">Début de la Période d'Essai</label>
                                    <input type="date" name="trial_period_start" id="trial_period_start" class="form-control @error('trial_period_start') is-invalid @enderror" value="{{ old('trial_period_start', $contract->trial_period_start ? $contract->trial_period_start->format('Y-m-d') : '') }}">
                                    @error('trial_period_start')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="trial_period_end">Fin de la Période d'Essai</label>
                                    <input type="date" name="trial_period_end" id="trial_period_end" class="form-control @error('trial_period_end') is-invalid @enderror" value="{{ old('trial_period_end', $contract->trial_period_end ? $contract->trial_period_end->format('Y-m-d') : '') }}">
                                    @error('trial_period_end')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="benefits">Avantages</label>
                                    <textarea name="benefits" id="benefits" class="form-control @error('benefits') is-invalid @enderror" rows="3">{{ old('benefits', $contract->benefits) }}</textarea>
                                    @error('benefits')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="contract_file">Document du Contrat</label>
                                    @if($contract->contract_file)
                                        <div class="mb-2">
                                            <a href="{{ Storage::url($contract->contract_file) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-download"></i> Document actuel
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file" name="contract_file" id="contract_file" class="form-control-file @error('contract_file') is-invalid @enderror" accept=".pdf,.doc,.docx">
                                    <small class="form-text text-muted">Laissez vide pour conserver le document actuel</small>
                                    @error('contract_file')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="hiring_form">Fiche d'Embauche</label>
                                    @if($contract->hiring_form)
                                        <div class="mb-2">
                                            <a href="{{ Storage::url($contract->hiring_form) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-download"></i> Fiche actuelle
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file" name="hiring_form" id="hiring_form" class="form-control-file @error('hiring_form') is-invalid @enderror" accept=".pdf,.doc,.docx">
                                    <small class="form-text text-muted">Laissez vide pour conserver la fiche actuelle</small>
                                    @error('hiring_form')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        @if($contract->status === 'terminated')
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h5><i class="fas fa-exclamation-triangle"></i> Contrat Résilié</h5>
                                    <p><strong>Date de résiliation :</strong> {{ $contract->terminated_at->format('d/m/Y') }}</p>
                                    <p><strong>Raison :</strong> {{ $contract->termination_reason }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        <a href="{{ route('contracts.show', $contract) }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('type').addEventListener('change', function() {
    const endDateField = document.getElementById('end_date');
    if (this.value === 'CDI') {
        endDateField.removeAttribute('required');
        endDateField.value = '';
    } else {
        endDateField.setAttribute('required', 'required');
    }
});

// Auto-calculate trial period end date
document.getElementById('trial_period_start').addEventListener('change', function() {
    const startDate = this.value;
    const trialMonths = document.getElementById('trial_period_months').value;
    
    if (startDate && trialMonths) {
        const endDate = new Date(startDate);
        endDate.setMonth(endDate.getMonth() + parseInt(trialMonths));
        
        const formattedDate = endDate.toISOString().split('T')[0];
        document.getElementById('trial_period_end').value = formattedDate;
    }
});

document.getElementById('trial_period_months').addEventListener('change', function() {
    const startDate = document.getElementById('trial_period_start').value;
    const trialMonths = this.value;
    
    if (startDate && trialMonths) {
        const endDate = new Date(startDate);
        endDate.setMonth(endDate.getMonth() + parseInt(trialMonths));
        
        const formattedDate = endDate.toISOString().split('T')[0];
        document.getElementById('trial_period_end').value = formattedDate;
    }
});

// Déclencher l'événement au chargement pour initialiser correctement
document.getElementById('type').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection