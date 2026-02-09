@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Détails de Présence</h2>

        <div class="flex gap-3">
            <a href="{{ route('hr.attendances.index') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Retour
            </a>


        </div>
    </div>

    <!-- CARD -->
    <div class="bg-white shadow-md rounded-xl p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- INFORMATIONS -->
            <div>
                <table class="w-full text-left border border-gray-200 rounded-lg overflow-hidden">
                    <tbody class="divide-y divide-gray-200">
                        
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 font-semibold text-gray-800 w-40">Employé</th>
                            <td class="px-4 py-3">{{ $attendance->employee->full_name }}</td>
                        </tr>

                        <tr>
                            <th class="px-4 py-3 font-semibold text-gray-800">Département</th>
                            <td class="px-4 py-3">{{ $attendance->employee->department->nom }}</td>
                        </tr>

                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 font-semibold text-gray-800">Date</th>
                            <td class="px-4 py-3">{{ $attendance->date->format('d/m/Y') }}</td>
                        </tr>

                        <tr>
                            <th class="px-4 py-3 font-semibold text-gray-800">Heure d'Arrivée</th>
                            <td class="px-4 py-3">
                                {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}
                            </td>
                        </tr>

                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 font-semibold text-gray-800">Heure de Départ</th>
                            <td class="px-4 py-3">
                                {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th class="px-4 py-3 font-semibold text-gray-800">Statut</th>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-white text-sm
                                    @if($attendance->status_color === 'success') bg-green-600
                                    @elseif($attendance->status_color === 'warning') bg-yellow-500
                                    @elseif($attendance->status_color === 'danger') bg-red-600
                                    @else bg-gray-600
                                    @endif">
                                    {{ $attendance->status_label }}
                                </span>
                            </td>
                        </tr>

                        @if($attendance->late_minutes)
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 font-semibold text-gray-800">Retard</th>
                            <td class="px-4 py-3">{{ $attendance->late_minutes }} minutes</td>
                        </tr>
                        @endif

                        @if($attendance->overtime_hours)
                        <tr>
                            <th class="px-4 py-3 font-semibold text-gray-800">Heures Supplémentaires</th>
                            <td class="px-4 py-3">{{ $attendance->overtime_hours }} heures</td>
                        </tr>
                        @endif

                        <!-- Heures totalisées -->
                        <tr class="bg-primary-50">
                            <th class="px-4 py-3 font-bold text-primary-700">Heures totalisées</th>
                            <td class="px-4 py-3 font-semibold text-primary-700">
                                {{ $attendance->total_hours ?? '0h' }}
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- PHOTOS + NOTES + QR CODE -->
            <div class="space-y-6">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h5 class="font-semibold mb-2">Photo d’arrivée</h5>
                        @if($attendance->check_in_photo)
                            <img src="{{ Storage::url($attendance->check_in_photo) }}"
                                 class="rounded-lg border shadow">
                        @else
                            <p class="text-gray-500">Pas de photo</p>
                        @endif
                    </div>

                    <div>
                        <h5 class="font-semibold mb-2">Photo de départ</h5>
                        @if($attendance->check_out_photo)
                            <img src="{{ Storage::url($attendance->check_out_photo) }}"
                                 class="rounded-lg border shadow">
                        @else
                            <p class="text-gray-500">Pas de photo</p>
                        @endif
                    </div>
                </div>

                @if($attendance->notes)
                <div>
                    <h5 class="font-semibold mb-2">Notes</h5>
                    <p class="p-3 bg-gray-100 rounded-lg">{{ $attendance->notes }}</p>
                </div>
                @endif



            </div>
        </div>

    </div>
</div>
@endsection
