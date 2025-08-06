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
                                    <label for="contract_type">Type de Contrat</label>
                                    <select name="contract_type" id="contract_type" class="form-control @error('contract_type') is-invalid @enderror" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="CDI" {{ old('contract_type', $contract->contract_type) == 'CDI' ? 'selected' : '' }}>CDI</option>
                                        <option value="CDD" {{ old('contract_type', $contract->contract_type) == 'CDD' ? 'selected' : '' }}>CDD</option>
                                        <option value="Stage" {{ old('contract_type', $contract->contract_type) == 'Stage' ? 'selected' : '' }}>Stage</option>
                                        <option value="Intérim" {{ old('contract_type', $contract->contract_type) == 'Intérim' ? 'selected' : '' }}>Intérim</option>
                                    </select>
                                    @error('contract_type')
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
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="base_salary">Salaire de Base</label>
                                    <input type="number" name="base_salary" id="base_salary" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary', $contract->base_salary) }}" required>
                                    @error('base_salary')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="trial_period">Période d'Essai (en mois)</label>
                                    <input type="number" name="trial_period" id="trial_period" class="form-control @error('trial_period') is-invalid @enderror" value="{{ old('trial_period', $contract->trial_period) }}">
                                    @error('trial_period')
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
                            </div>
                        </div>
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
document.getElementById('contract_type').addEventListener('change', function() {
    const endDateField = document.getElementById('end_date');
    if (this.value === 'CDI') {
        endDateField.removeAttribute('required');
        endDateField.value = '';
    } else {
        endDateField.setAttribute('required', 'required');
    }
});

// Déclencher l'événement au chargement pour initialiser correctement
document.getElementById('contract_type').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection