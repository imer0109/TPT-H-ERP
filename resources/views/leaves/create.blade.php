@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nouvelle Demande de Congé</h3>
                </div>
                <form action="{{ route('leaves.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @if(auth()->user()->can('create-leave-for-others'))
                        <div class="form-group">
                            <label for="employee_id">Employé</label>
                            <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                <option value="">Sélectionner un employé</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="leave_type_id">Type de Congé</label>
                            <select name="leave_type_id" id="leave_type_id" class="form-control @error('leave_type_id') is-invalid @enderror" required>
                                <option value="">Sélectionner un type</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" data-days="{{ $type->default_days }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} ({{ $type->default_days }} jours/an)
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Date de Début</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Date de Fin</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reason">Motif</label>
                            <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="supporting_document">Document Justificatif</label>
                            <input type="file" name="supporting_document" id="supporting_document" class="form-control-file @error('supporting_document') is-invalid @enderror">
                            <small class="form-text text-muted">Formats acceptés : PDF, JPG, PNG (max 2MB)</small>
                            @error('supporting_document')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-info" id="leave-info" style="display: none;">
                            <p>Jours de congé disponibles : <span id="available-days">0</span></p>
                            <p>Durée demandée : <span id="requested-days">0</span> jours</p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Soumettre</button>
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const leaveType = document.getElementById('leave_type_id');
    const leaveInfo = document.getElementById('leave-info');
    const availableDays = document.getElementById('available-days');
    const requestedDays = document.getElementById('requested-days');

    function updateDays() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            requestedDays.textContent = diffDays;

            const selectedType = leaveType.options[leaveType.selectedIndex];
            if (selectedType && selectedType.dataset.days) {
                availableDays.textContent = selectedType.dataset.days;
                leaveInfo.style.display = 'block';
            }
        }
    }

    startDate.addEventListener('change', updateDays);
    endDate.addEventListener('change', updateDays);
    leaveType.addEventListener('change', updateDays);
});
</script>
@endpush
@endsection