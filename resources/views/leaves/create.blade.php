@extends('layouts.app')

@section('content')
@php
use Illuminate\Support\Facades\Auth;
@endphp
<div class="min-h-screen bg-gray-50 py-10 px-6">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-2xl font-semibold text-gray-800">
                Nouvelle Demande de Congé 
                @if(!auth()->user()->can('create-leave-for-others'))
                    pour {{ Auth::user()->employee->full_name ?? Auth::user()->name }}
                @endif
            </h3>
        </div>

        <form action="{{ route('hr.leaves.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            @if(auth()->user()->can('create-leave-for-others'))
            <div>
                <label for="employee_search" class="block text-gray-700 font-medium mb-2">Rechercher un Employé</label>
                <input type="text" id="employee_search" placeholder="Tapez pour rechercher un employé..." 
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 mb-2">
                
                <label for="employee_id" class="block text-gray-700 font-medium mb-2">Employé</label>
                <select name="employee_id" id="employee_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('employee_id') border-red-500 @enderror" required>
                    <option value="">Sélectionner un employé</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" data-name="{{ strtolower($employee->full_name) }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @else
                <!-- Hidden field for self-service leave request -->
                @php
                    $currentUserEmployee = Auth::user()->employee;
                @endphp
                @if($currentUserEmployee)
                    <input type="hidden" name="employee_id" value="{{ $currentUserEmployee->id }}">
                @else
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Erreur!</strong>
                        <span class="block sm:inline">Impossible de déterminer votre profil employé. Veuillez contacter l'administrateur.</span>
                    </div>
                @endif
            @endif

            <div>
                <label for="leave_type_id" class="block text-gray-700 font-medium mb-2">Type de Congé</label>
                <select name="leave_type_id" id="leave_type_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('leave_type_id') border-red-500 @enderror" required>
                    <option value="">Sélectionner un type</option>
                    @foreach($leaveTypes as $type)
                        <option value="{{ $type->id }}" data-days="{{ $type->default_days }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }} ({{ $type->default_days }} jours/an)
                        </option>
                    @endforeach
                </select>
                @error('leave_type_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-gray-700 font-medium mb-2">Date de Début</label>
                    <input type="date" name="start_date" id="start_date"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('start_date') border-red-500 @enderror"
                        value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_date" class="block text-gray-700 font-medium mb-2">Date de Fin</label>
                    <input type="date" name="end_date" id="end_date"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('end_date') border-red-500 @enderror"
                        value="{{ old('end_date') }}" required>
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="reason" class="block text-gray-700 font-medium mb-2">Motif</label>
                <textarea name="reason" id="reason" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('reason') border-red-500 @enderror"
                    required>{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="supporting_document" class="block text-gray-700 font-medium mb-2">Document Justificatif</label>
                <input type="file" name="supporting_document" id="supporting_document"
                    class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 @error('supporting_document') border-red-500 @enderror">
                <small class="block text-gray-500 mt-1">Formats acceptés : PDF, JPG, PNG (max 2MB)</small>
                @error('supporting_document')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="leave-info" class="hidden bg-primary-50 border border-primary-200 rounded-lg p-4 text-primary-800">
                <p>Jours de congé disponibles : <span id="available-days" class="font-semibold">0</span></p>
                <p>Durée demandée : <span id="requested-days" class="font-semibold">0</span> jours</p>
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-200 pt-6">
                <a href="{{ route('hr.leaves.index') }}"
                    class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">Annuler</a>
                <button type="submit"
                    class="px-5 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition">
                    Soumettre
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Script pour la recherche d'employés
    const employeeSearch = document.getElementById('employee_search');
    const employeeSelect = document.getElementById('employee_id');
    const employeeOptions = employeeSelect.querySelectorAll('option');
    
    if (employeeSearch) {
        employeeSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            employeeOptions.forEach(option => {
                if (option.value === '') {
                    // Toujours afficher l'option par défaut
                    option.style.display = '';
                    return;
                }
                
                const employeeName = option.getAttribute('data-name');
                if (employeeName.includes(searchTerm)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
            
            // Réinitialiser la sélection si aucun terme de recherche
            if (searchTerm === '') {
                employeeSelect.value = '';
            }
        });
    }
    
    // Scripts existants pour les dates et les types de congé
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
                leaveInfo.classList.remove('hidden');
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