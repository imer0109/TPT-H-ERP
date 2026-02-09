@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-700">Rapport d'Effectifs (Headcount)</h2>
            <ol class="flex space-x-2 text-gray-500 text-sm hidden print:flex">
                <li><a href="{{ route('dashboard') }}" class="text-primary-600">Tableau de bord</a></li>
                <li>/</li>
                <li>Rapport d'Effectifs</li>
            </ol>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-primary-500 p-4 rounded shadow-sm flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Total Employés</p>
                <h3 class="text-primary-600 text-xl font-bold">{{ $employees->count() }}</h3>
            </div>
            <i class="mdi mdi-account-group text-primary-500 text-2xl"></i>
        </div>
        <div class="bg-white border border-green-500 p-4 rounded shadow-sm flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Employés Actifs</p>
                <h3 class="text-green-600 text-xl font-bold">{{ $employees->where('status', 'active')->count() }}</h3>
            </div>
            <i class="mdi mdi-account-check text-green-500 text-2xl"></i>
        </div>
        <div class="bg-white border border-yellow-500 p-4 rounded shadow-sm flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Suspendus</p>
                <h3 class="text-yellow-600 text-xl font-bold">{{ $employees->where('status', 'suspended')->count() }}</h3>
            </div>
            <i class="mdi mdi-account-clock text-yellow-500 text-2xl"></i>
        </div>
        <div class="bg-white border border-red-500 p-4 rounded shadow-sm flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Archivés</p>
                <h3 class="text-red-600 text-xl font-bold">{{ $employees->where('status', 'archived')->count() }}</h3>
            </div>
            <i class="mdi mdi-account-off text-red-500 text-2xl"></i>
        </div>
    </div>

    <!-- Breakdown Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

        <!-- By Company -->
        <div class="bg-white rounded shadow-sm p-4">
            <h4 class="text-lg font-semibold mb-3">Répartition par Société</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-3 text-left">Société</th>
                            <th class="py-2 px-3 text-center">Effectif</th>
                            <th class="py-2 px-3 text-center">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $companyCounts = $employees->groupBy('currentCompany.raison_sociale')->map(fn($group) => $group->count());
                            $totalEmployees = $employees->count();
                        @endphp
                        @foreach($companyCounts as $companyName => $count)
                        <tr class="border-b">
                            <td class="py-2 px-3">{{ $companyName ?: 'Non assigné' }}</td>
                            <td class="py-2 px-3 text-center">{{ $count }}</td>
                            <td class="py-2 px-3 text-center">{{ $totalEmployees > 0 ? round(($count / $totalEmployees) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- By Position -->
        <div class="bg-white rounded shadow-sm p-4">
            <h4 class="text-lg font-semibold mb-3">Répartition par Poste</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-3 text-left">Poste</th>
                            <th class="py-2 px-3 text-center">Effectif</th>
                            <th class="py-2 px-3 text-center">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $positionCounts = $employees->groupBy('currentPosition.title')->map(fn($group) => $group->count());
                        @endphp
                        @foreach($positionCounts as $positionName => $count)
                        <tr class="border-b">
                            <td class="py-2 px-3">{{ $positionName ?: 'Non assigné' }}</td>
                            <td class="py-2 px-3 text-center">{{ $count }}</td>
                            <td class="py-2 px-3 text-center">{{ $totalEmployees > 0 ? round(($count / $totalEmployees) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- By Agency -->
        <div class="bg-white rounded shadow-sm p-4">
            <h4 class="text-lg font-semibold mb-3">Répartition par Agence</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-3 text-left">Agence</th>
                            <th class="py-2 px-3 text-center">Effectif</th>
                            <th class="py-2 px-3 text-center">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $agencyCounts = $employees->groupBy('currentAgency.nom')->map(fn($group) => $group->count());
                        @endphp
                        @foreach($agencyCounts as $agencyName => $count)
                        <tr class="border-b">
                            <td class="py-2 px-3">{{ $agencyName ?: 'Non assigné' }}</td>
                            <td class="py-2 px-3 text-center">{{ $count }}</td>
                            <td class="py-2 px-3 text-center">{{ $totalEmployees > 0 ? round(($count / $totalEmployees) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- By Status -->
        <div class="bg-white rounded shadow-sm p-4">
            <h4 class="text-lg font-semibold mb-3">Répartition par Statut</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-3 text-left">Statut</th>
                            <th class="py-2 px-3 text-center">Effectif</th>
                            <th class="py-2 px-3 text-center">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $statusCounts = $employees->groupBy('status')->map(fn($group) => $group->count());
                        @endphp
                        @foreach($statusCounts as $status => $count)
                        <tr class="border-b">
                            <td class="py-2 px-3">
                                <span class="px-2 py-1 rounded-full text-white {{ $status === 'active' ? 'bg-green-500' : ($status === 'suspended' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-center">{{ $count }}</td>
                            <td class="py-2 px-3 text-center">{{ $totalEmployees > 0 ? round(($count / $totalEmployees) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detailed Employee List -->
    <div class="bg-white rounded shadow-sm p-4">
        <h4 class="text-lg font-semibold mb-3">Liste Détaillée des Employés</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-3">Matricule</th>
                        <th class="py-2 px-3">Nom Complet</th>
                        <th class="py-2 px-3">Poste</th>
                        <th class="py-2 px-3">Société</th>
                        <th class="py-2 px-3">Agence</th>
                        <th class="py-2 px-3">Statut</th>
                        <th class="py-2 px-3">Date Embauche</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    <tr class="border-b">
                        <td class="py-2 px-3">{{ $employee->matricule }}</td>
                        <td class="py-2 px-3 flex items-center gap-2">
                            {{-- <img src="{{ $employee->photo ? asset('storage/' . $employee->photo) : asset('images/users/avatar-default.jpg') }}" class="w-8 h-8 rounded-full"> --}}
                            <span>{{ $employee->prenom }} {{ $employee->nom }}</span>
                        </td>
                        <td class="py-2 px-3">{{ $employee->currentPosition->title ?? 'N/A' }}</td>
                        <td class="py-2 px-3">{{ $employee->currentCompany->raison_sociale ?? 'N/A' }}</td>
                        <td class="py-2 px-3">{{ $employee->currentAgency->nom ?? 'N/A' }}</td>
                        <td class="py-2 px-3">
                            <span class="px-2 py-1 rounded-full text-white {{ $employee->status === 'active' ? 'bg-green-500' : ($employee->status === 'suspended' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </td>
                        <td class="py-2 px-3">{{ $employee->date_embauche ? \Carbon\Carbon::parse($employee->date_embauche)->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
