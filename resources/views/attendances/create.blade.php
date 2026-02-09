@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-2xl p-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Nouveau Pointage</h2>

        <form action="{{ route('hr.attendances.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Employé -->
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Employé*</label>
                    <select name="employee_id" id="employee_id"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Sélectionner un employé</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->first_name }} {{ $employee->last_name }}
                            @if($employee->currentPosition)
                                - {{ $employee->currentPosition->title }}
                            @endif
                        </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date*</label>
                    <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Heure d'Arrivée -->
                <div>
                    <label for="check_in" class="block text-sm font-medium text-gray-700 mb-1">Heure d'Arrivée</label>
                    <input type="time" name="check_in" id="check_in" value="{{ old('check_in', date('H:i')) }}"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('check_in')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Heure de Départ -->
                <div>
                    <label for="check_out" class="block text-sm font-medium text-gray-700 mb-1">Heure de Départ</label>
                    <input type="time" name="check_out" id="check_out" value="{{ old('check_out') }}"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('check_out')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut*</label>
                    <select name="status" id="status"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Sélectionner un statut</option>
                        <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Présent</option>
                        <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>En retard</option>
                        <option value="half_day" {{ old('status') == 'half_day' ? 'selected' : '' }}>Demi-journée</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Minutes de Retard -->
                <div>
                    <label for="late_minutes" class="block text-sm font-medium text-gray-700 mb-1">Minutes de Retard</label>
                    <input type="number" name="late_minutes" id="late_minutes" value="{{ old('late_minutes', 0) }}" min="0"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('late_minutes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo de Pointage -->
                <div class="md:col-span-2">
                    <label for="check_in_photo" class="block text-sm font-medium text-gray-700 mb-1">Photo de Pointage (Optionnelle)</label>
                    <input type="file" name="check_in_photo" id="check_in_photo" accept="image/*"
                        class="w-full p-2 rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-gray-500 text-sm mt-1">Formats acceptés : JPG, PNG (max 2MB)</p>
                    @error('check_in_photo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Boutons -->
            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('hr.attendances.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const checkInField = document.getElementById('check_in');
    const checkOutField = document.getElementById('check_out');
    const lateMinutesField = document.getElementById('late_minutes');

    statusSelect.addEventListener('change', function() {
        if (this.value === 'absent') {
            checkInField.value = '';
            checkOutField.value = '';
            lateMinutesField.value = 0;
            checkInField.disabled = true;
            checkOutField.disabled = true;
        } else {
            checkInField.disabled = false;
            checkOutField.disabled = false;
        }
    });

    checkInField.addEventListener('change', function() {
        if (this.value && statusSelect.value !== 'absent') {
            const checkInTime = new Date('1970-01-01T' + this.value + ':00');
            const standardTime = new Date('1970-01-01T08:00:00');
            if (checkInTime > standardTime) {
                const diffMinutes = Math.floor((checkInTime - standardTime) / (1000 * 60));
                lateMinutesField.value = diffMinutes;
                if (statusSelect.value === 'present') {
                    statusSelect.value = 'late';
                }
            } else {
                lateMinutesField.value = 0;
            }
        }
    });
});
</script>
@endpush
@endsection
