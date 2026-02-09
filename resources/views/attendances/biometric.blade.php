@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto bg-white shadow-lg rounded-2xl overflow-hidden">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center bg-gradient-to-r from-primary-600 to-indigo-600 p-6">
            <h3 class="text-xl font-semibold text-white">Gestion des Dispositifs Biométriques</h3>
            <button type="button" 
                    onclick="syncEmployees()" 
                    class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-white text-primary-700 font-semibold rounded-lg shadow hover:bg-primary-100 transition">
                <i class="fas fa-sync-alt mr-2"></i> Synchroniser les Employés
            </button>
        </div>

        <!-- Device List -->
        <div class="p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Dispositifs Connectés</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($devices as $device)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h5 class="font-medium text-gray-900">{{ $device->device_name ?? 'Dispositif Biométrique' }}</h5>
                            <p class="text-sm text-gray-500">ID: {{ $device->device_id }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Connecté</span>
                    </div>
                    <div class="mt-3 text-sm text-gray-600">
                        <p>Type: {{ $device->device_type ?? 'Non spécifié' }}</p>
                        <p>Dernière activité: {{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mt-4 flex space-x-2">
                        <button type="button" 
                                onclick="viewDeviceAttendance('{{ $device->device_id }}')" 
                                class="text-sm px-3 py-1 bg-primary-100 text-primary-700 rounded hover:bg-primary-200 transition">
                            Voir les pointages
                        </button>
                        <button type="button" 
                                onclick="syncDevice('{{ $device->device_id }}')" 
                                class="text-sm px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition">
                            <i class="fas fa-sync-alt mr-1"></i> Sync
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-8 text-gray-500">
                    <i class="fas fa-fingerprint text-4xl mb-4"></i>
                    <p>Aucun dispositif biométrique connecté</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Attendance Data -->
        <div class="p-6 border-t border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Derniers Pointages Biométriques</h4>
            
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-primary-50">
                        <tr class="text-left text-sm font-semibold text-gray-700">
                            <th class="px-6 py-3">Employé</th>
                            <th class="px-6 py-3">Dispositif</th>
                            <th class="px-6 py-3">Type Biométrique</th>
                            <th class="px-6 py-3">Date & Heure</th>
                            <th class="px-6 py-3">Type</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @forelse($biometricAttendances as $attendance)
                        <tr class="hover:bg-primary-50">
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $attendance->employee->full_name }}</td>
                            <td class="px-6 py-3">{{ $attendance->device_name ?? $attendance->device_id }}</td>
                            <td class="px-6 py-3">{{ $attendance->biometric_type_name }}</td>
                            <td class="px-6 py-3">{{ $attendance->biometric_timestamp->format('d/m/Y H:i:s') }}</td>
                            <td class="px-6 py-3">
                                @if($attendance->check_in && !$attendance->check_out)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Entrée</span>
                                @elseif($attendance->check_out)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-primary-100 text-primary-800">Sortie</span>
                                @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inconnu</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Aucun pointage biométrique enregistré
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function syncEmployees() {
    if (confirm('Souhaitez-vous synchroniser les employés avec les dispositifs biométriques ?')) {
        fetch('{{ route("attendances.sync-employees") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Synchronisation réussie : ${data.count} employés synchronisés`);
                location.reload();
            } else {
                alert('Erreur lors de la synchronisation : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la synchronisation');
        });
    }
}

function syncDevice(deviceId) {
    if (confirm(`Souhaitez-vous synchroniser le dispositif ${deviceId} ?`)) {
        // In a real implementation, this would make an AJAX call to sync the specific device
        alert(`Synchronisation du dispositif ${deviceId} initiée.`);
    }
}

function viewDeviceAttendance(deviceId) {
    // In a real implementation, this would redirect to a page showing attendance for this device
    alert(`Affichage des pointages pour le dispositif ${deviceId}`);
}
</script>
@endpush
@endsection