@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Tableau de Bord Gestionnaire</h1>
        <div class="text-gray-600">Bienvenue, {{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
    </div>

    <form method="get" class="grid grid-cols-1 md:grid-cols-5 gap-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Société</label>
            <select name="company_id" class="w-full border rounded px-3 py-2">
                <option value="">Toutes</option>
                @foreach($companies as $c)
                    <option value="{{ $c->id }}" {{ (string)$c->id === (string)$companyId ? 'selected' : '' }}>{{ $c->raison_sociale }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Agence</label>
            <select name="agency_id" class="w-full border rounded px-3 py-2">
                <option value="">Toutes</option>
                @foreach($agencies as $a)
                    <option value="{{ $a->id }}" {{ (string)$a->id === (string)$agencyId ? 'selected' : '' }}>{{ $a->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Du</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Au</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full border rounded px-3 py-2">
        </div>
        <div class="flex items-end">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 w-full">Filtrer</button>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <a href="{{ route('companies.dashboard') }}" class="bg-white rounded-lg shadow p-6 block hover:bg-red-50 transition">
            <div class="text-sm text-gray-500">Projets actifs</div>
            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $activeProjects }}</div>
        </a>
        <a href="{{ route('teams.index') }}" class="bg-white rounded-lg shadow p-6 block hover:bg-red-50 transition">
            <div class="text-sm text-gray-500">Équipes</div>
            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalTeams }}</div>
        </a>
        <a href="{{ route('validations.requests.index') }}?status=pending&company_id={{ $companyId }}&agency_id={{ $agencyId }}&date_from={{ $dateFrom }}&date_to={{ $dateTo }}" class="bg-white rounded-lg shadow p-6 block hover:bg-red-50 transition">
            <div class="text-sm text-gray-500">Validations en attente</div>
            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $pendingValidations }}</div>
        </a>
        <a href="{{ route('validations.requests.index') }}?status=approved&company_id={{ $companyId }}&agency_id={{ $agencyId }}&date_from={{ $dateFrom }}&date_to={{ $dateTo }}" class="bg-white rounded-lg shadow p-6 block hover:bg-red-50 transition">
            <div class="text-sm text-gray-500">Validations approuvées (aujourd'hui)</div>
            <div class="mt-2 text-3xl font-semibold text-green-600">{{ $approvedValidationsToday }}</div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Performance des équipes</h3>
            <div class="h-64">
                <canvas id="managerTeamPerformance"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Charge de travail</h3>
            <div class="h-64">
                <canvas id="managerWorkload"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const teamCtx = document.getElementById('managerTeamPerformance').getContext('2d');
    const perfData = @json($teamPerformanceSeries);
    const perfDatasets = perfData.teams.map(t => ({
        label: t.name,
        data: t.data,
        borderColor: '#3B82F6',
        backgroundColor: 'rgba(59,130,246,0.1)',
        fill: true,
        tension: 0.4
    }));
    new Chart(teamCtx, { type: 'line', data: { labels: perfData.labels, datasets: perfDatasets }, options: { responsive: true, maintainAspectRatio: false } });

    const workloadCtx = document.getElementById('managerWorkload').getContext('2d');
    const wl = @json($workloadByModule);
    const wlLabels = wl.map(i => i.module);
    const wlData = wl.map(i => i.count);
    new Chart(workloadCtx, { type: 'bar', data: { labels: wlLabels, datasets: [{ label: 'Validations en attente', data: wlData, backgroundColor: ['#3B82F6', '#EF4444', '#F59E0B', '#10B981', '#6366F1'], borderRadius: 4 }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } } });
});
</script>
@endsection
