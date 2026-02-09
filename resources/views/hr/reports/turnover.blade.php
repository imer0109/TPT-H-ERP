@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6 bg-gray-50 min-h-screen">

    <!-- Page title & breadcrumb -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Rapport de Rotation du Personnel ({{ $year }})
            </h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a href="{{ route('dashboard') }}" class="hover:text-primary-600">Tableau de bord</a>
                <span class="mx-2">/</span>
                <span class="text-gray-700 font-medium">Rapport de rotation</span>
            </nav>
        </div>

        <!-- Year filter -->
        <form method="GET" class="mt-4 md:mt-0">
            <select name="year"
                onchange="this.form.submit()"
                class="px-4 py-2 border rounded-lg focus:ring focus:ring-primary-300">
                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </form>
    </div>

    <!-- Summary cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-primary-500">
            <p class="text-sm text-gray-500">Nouvelles embauches</p>
            <h2 class="text-3xl font-bold text-primary-600 mt-2">0</h2>
            <p class="text-sm text-green-600 mt-1">+0% vs année précédente</p>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Départs</p>
            <h2 class="text-3xl font-bold text-yellow-600 mt-2">0</h2>
            <p class="text-sm text-red-500 mt-1">-0% vs année précédente</p>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-indigo-500">
            <p class="text-sm text-gray-500">Taux de rotation</p>
            <h2 class="text-3xl font-bold text-indigo-600 mt-2">0%</h2>
            <p class="text-sm text-gray-500 mt-1">Objectif &lt; 15%</p>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Effectif moyen</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">0</h2>
            <p class="text-sm text-green-600 mt-1">Croissance stable</p>
        </div>

    </div>

    <!-- Charts section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl shadow p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Évolution mensuelle de la rotation
            </h3>
            <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                <span class="text-5xl">📈</span>
                <p class="mt-3 text-sm">
                    Les données seront affichées ici ultérieurement
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Rotation par département
            </h3>
            <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                <span class="text-5xl">🏢</span>
                <p class="mt-3 text-sm text-center">
                    Aucune donnée disponible
                </p>
            </div>
        </div>

    </div>

    <!-- Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Motifs de départ -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Motifs de départ</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-gray-600">
                        <th class="text-left py-2">Motif</th>
                        <th class="text-center py-2">Nombre</th>
                        <th class="text-center py-2">%</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" class="text-center py-6 text-gray-400">
                            Aucun départ enregistré pour {{ $year }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Exit interviews -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">
                Entretiens de sortie
            </h3>
            <div class="flex flex-col items-center justify-center h-40 text-gray-400">
                <span class="text-4xl">🗣️</span>
                <p class="text-sm mt-2">
                    Aucun entretien enregistré
                </p>
            </div>
        </div>

    </div>

    <!-- Analysis -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4">
            Analyse et recommandations
        </h3>

        <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 text-primary-800">
            <strong>État actuel :</strong>
            Les données pour {{ $year }} sont en cours de collecte.
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

            <div>
                <h4 class="font-semibold mb-2">Points positifs</h4>
                <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                    <li>Stabilité de l’effectif</li>
                    <li>Rotation maîtrisée</li>
                    <li>Compétences clés conservées</li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-2">Recommandations</h4>
                <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                    <li>Entretiens de sortie systématiques</li>
                    <li>Suivi de la satisfaction employé</li>
                    <li>Plan de rétention des talents</li>
                </ul>
            </div>

        </div>
    </div>

</div>
@endsection
