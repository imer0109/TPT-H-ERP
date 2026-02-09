@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-6 space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Rapport de Présence ({{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }})
            </h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a href="{{ route('dashboard') }}" class="hover:text-primary-600">Tableau de bord</a>
                <span class="mx-2">/</span>
                <span class="text-gray-700 font-medium">Rapport de Présence</span>
            </nav>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow p-6">
        <form method="GET" action="{{ route('hr.reports.attendance') }}"
              class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

            <div>
                <label class="text-sm font-medium text-gray-600">Mois</label>
                <select name="month"
                        class="w-full mt-1 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Année</label>
                <select name="year"
                        class="w-full mt-1 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    @for($y = date('Y') - 5; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex gap-2">
                <button class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-lg">
                    🔍 Filtrer
                </button>
                <a href="{{ route('hr.reports.attendance') }}"
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg text-center">
                    🔄 Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-primary-500">
            <p class="text-sm text-gray-500">Jours de Présence</p>
            <h2 class="text-3xl font-bold text-primary-600 mt-2">{{ $attendanceData['present'] ?? 0 }}</h2>
            <p class="text-sm text-gray-400 mt-1">jours présents</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Taux de Présence</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">{{ $attendanceData['attendance_rate'] ?? 0 }}%</h2>
            <p class="text-sm text-gray-400 mt-1">taux moyen</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Retards</p>
            <h2 class="text-3xl font-bold text-yellow-500 mt-2">{{ $attendanceData['late'] ?? 0 }}</h2>
            <p class="text-sm text-gray-400 mt-1">jours en retard</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-indigo-500">
            <p class="text-sm text-gray-500">Heures Supplémentaires</p>
            <h2 class="text-3xl font-bold text-indigo-600 mt-2">{{ round(($attendanceData['overtime_minutes'] ?? 0) / 60, 1) }}h</h2>
            <p class="text-sm text-gray-400 mt-1">total mensuel</p>
        </div>

    </div>

    <!-- Attendance Analysis & Key Indicators -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Table -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Analyse de Présence</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="text-left p-2">Statut</th>
                            <th class="text-center p-2">Nombre de Jours</th>
                            <th class="text-center p-2">Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr>
                            <td class="p-2">✅ Présent</td>
                            <td class="text-center">{{ $attendanceData['present'] ?? 0 }}</td>
                            <td class="text-center text-green-600 font-semibold">
                                @if(($attendanceData['total_days'] ?? 0) > 0)
                                    {{ round((($attendanceData['present'] ?? 0) / ($attendanceData['total_days'] ?? 1)) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">❌ Absent</td>
                            <td class="text-center">{{ $attendanceData['absent'] ?? 0 }}</td>
                            <td class="text-center text-red-600 font-semibold">
                                @if(($attendanceData['total_days'] ?? 0) > 0)
                                    {{ round((($attendanceData['absent'] ?? 0) / ($attendanceData['total_days'] ?? 1)) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">⏰ En Retard</td>
                            <td class="text-center">{{ $attendanceData['late'] ?? 0 }}</td>
                            <td class="text-center text-yellow-500 font-semibold">
                                @if(($attendanceData['total_days'] ?? 0) > 0)
                                    {{ round((($attendanceData['late'] ?? 0) / ($attendanceData['total_days'] ?? 1)) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                        <tr class="bg-gray-200 font-semibold">
                            <td class="p-2">Total</td>
                            <td class="text-center">{{ $attendanceData['total_days'] ?? 0 }}</td>
                            <td class="text-center">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Key Indicators -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Indicateurs Clés</h3>

            <div class="space-y-4 text-sm">
                <div>
                    <div class="flex justify-between">
                        <span>Minutes de Retard Total</span>
                        <span class="font-bold text-primary-600">{{ $attendanceData['late_minutes'] ?? 0 }} min</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded mt-1">
                        <div class="h-2 bg-primary-500 rounded w-{{ min(100, ($attendanceData['late_minutes'] ?? 0) / 10) }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between">
                        <span>Heures Supplémentaires</span>
                        <span class="font-bold text-green-600">{{ round(($attendanceData['overtime_minutes'] ?? 0) / 60, 1) }}h</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded mt-1">
                        <div class="h-2 bg-green-500 rounded w-{{ min(100, ($attendanceData['overtime_minutes'] ?? 0) / 600) }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between">
                        <span>Taux de Ponctualité</span>
                        <span class="font-bold text-indigo-600">
                            @if(($attendanceData['total_days'] ?? 0) > 0)
                                {{ round((1 - (($attendanceData['late'] ?? 0) / ($attendanceData['total_days'] ?? 1))) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded mt-1">
                        <div class="h-2 bg-indigo-500 rounded w-{{ ($attendanceData['total_days'] ?? 0) > 0 ? round((1 - (($attendanceData['late'] ?? 0) / ($attendanceData['total_days'] ?? 1))) * 100, 1) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Recommandations</h3>

        <div class="bg-primary-50 p-4 rounded space-y-2 text-sm text-gray-700">
            <p>
                <strong>Taux de Présence:</strong> {{ $attendanceData['attendance_rate'] ?? 0 }}% - 
                @if(($attendanceData['attendance_rate'] ?? 0) >= 95)
                    Excellent taux de présence
                @elseif(($attendanceData['attendance_rate'] ?? 0) >= 90)
                    Bon taux de présence
                @elseif(($attendanceData['attendance_rate'] ?? 0) >= 80)
                    Taux acceptable mais améliorable
                @else
                    Taux préoccupant
                @endif
            </p>
            <p>
                <strong>Retards:</strong> {{ $attendanceData['late'] ?? 0 }} jours - 
                @if(($attendanceData['late'] ?? 0) == 0)
                    Aucun retard signalé
                @elseif(($attendanceData['late'] ?? 0) <= 3)
                    Quelques retards
                @else
                    Retards significatifs à surveiller
                @endif
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm text-gray-700">
            <div>
                <h6 class="font-semibold">Points Positifs:</h6>
                <ul class="list-disc list-inside space-y-1">
                    @if(($attendanceData['attendance_rate'] ?? 0) >= 90)<li>Taux de présence élevé</li>@endif
                    @if(($attendanceData['late'] ?? 0) <= 2)<li>Bonne ponctualité générale</li>@endif
                    @if(($attendanceData['overtime_minutes'] ?? 0) > 120)<li>Engagement démontré par les heures supplémentaires</li>@endif
                    <li>Respect des horaires de travail</li>
                </ul>
            </div>
            <div>
                <h6 class="font-semibold">Recommandations:</h6>
                <ul class="list-disc list-inside space-y-1">
                    @if(($attendanceData['attendance_rate'] ?? 0) < 90)<li>Mettre en place un système d'alerte pour absences répétées</li>@endif
                    @if(($attendanceData['late'] ?? 0) > 3)<li>Sensibiliser les employés à la ponctualité</li>@endif
                    @if(($attendanceData['overtime_minutes'] ?? 0) > 600)<li>Vérifier la charge de travail des équipes</li>@endif
                    <li>Encourager les bonnes pratiques de présence</li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection
