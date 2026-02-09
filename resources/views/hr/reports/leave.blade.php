@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-6 space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Rapport des Congés ({{ $year }})
            </h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a href="{{ route('dashboard') }}" class="hover:text-primary-600">Tableau de bord</a>
                <span class="mx-2">/</span>
                <span class="text-gray-700 font-medium">Rapport des Congés</span>
            </nav>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow p-6">
        <form method="GET" action="{{ route('hr.reports.leave') }}"
              class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

            <div>
                <label class="text-sm font-medium text-gray-600">Année</label>
                <select name="year"
                        class="w-full mt-1 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    @for($y = date('Y') - 5; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Type de Congé</label>
                <select name="leave_type"
                        class="w-full mt-1 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Tous les types</option>
                    <option value="annual">Congés Payés</option>
                    <option value="sick">Arrêt Maladie</option>
                    <option value="maternity">Congé Maternité</option>
                    <option value="personal">Congé Personnel</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-lg">
                    🔍 Filtrer
                </button>
                <a href="{{ route('hr.reports.leave') }}"
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg text-center">
                    🔄 Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-primary-500">
            <p class="text-sm text-gray-500">Total Congés</p>
            <h2 class="text-3xl font-bold text-primary-600 mt-2">0</h2>
            <p class="text-sm text-gray-400 mt-1">demandes</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Congés Approuvés</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">0</h2>
            <p class="text-sm text-gray-400 mt-1">validés</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">En Attente</p>
            <h2 class="text-3xl font-bold text-yellow-500 mt-2">0</h2>
            <p class="text-sm text-gray-400 mt-1">à valider</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Refusés</p>
            <h2 class="text-3xl font-bold text-red-600 mt-2">0</h2>
            <p class="text-sm text-gray-400 mt-1">rejetés</p>
        </div>

    </div>

    <!-- Analysis -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Table -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Analyse par Type de Congé</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="text-left p-2">Type</th>
                            <th class="text-center p-2">Demandes</th>
                            <th class="text-center p-2">Approuvées</th>
                            <th class="text-center p-2">Jours</th>
                            <th class="text-center p-2">Taux</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr>
                            <td class="p-2">🏖️ Congés Payés</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center text-green-600 font-semibold">0%</td>
                        </tr>
                        <tr>
                            <td class="p-2">🏥 Arrêt Maladie</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center text-green-600 font-semibold">0%</td>
                        </tr>
                        <tr>
                            <td class="p-2">👶 Congé Maternité</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center text-green-600 font-semibold">0%</td>
                        </tr>
                        <tr>
                            <td class="p-2">👤 Congé Personnel</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center text-green-600 font-semibold">0%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Indicators -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Indicateurs Clés</h3>

            <div class="space-y-4 text-sm">

                <div>
                    <div class="flex justify-between">
                        <span>Taux d'Absentéisme</span>
                        <span class="font-bold text-primary-600">0%</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded mt-1">
                        <div class="h-2 bg-primary-500 rounded w-0"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between">
                        <span>Jours Congés / Employé</span>
                        <span class="font-bold text-green-600">0.0</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded mt-1">
                        <div class="h-2 bg-green-500 rounded w-0"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between">
                        <span>Délai Moyen d'Approbation</span>
                        <span class="font-bold text-yellow-500">0 jours</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded mt-1">
                        <div class="h-2 bg-yellow-500 rounded w-0"></div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Actions -->
    <div class="flex flex-wrap justify-end gap-3">
        <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">📄 PDF</button>
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">📊 Excel</button>
        <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">✉️ Email</button>
    </div>

</div>
@endsection
