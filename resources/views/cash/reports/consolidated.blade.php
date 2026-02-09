@extends('layouts.app')

@section('title', 'Relevé des Mouvements de Trésorerie Consolidé')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Relevé des Mouvements de Trésorerie Consolidé</h1>
            <div class="flex space-x-2">
                <a href="{{ route('cash.reports.consolidated-movements.export') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Exporter
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Filtres</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="{{ route('cash.reports.consolidated-movements') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Consolidated Movements by Company -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Mouvements par Société</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-primary-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Société</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Encaissements</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Décaissements</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Solde</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $totalEncaissements = 0;
                                $totalDecaissements = 0;
                            @endphp
                            @foreach($movementsByCompany as $companyId => $data)
                                @php
                                    $totalEncaissements += $data['encaissements'];
                                    $totalDecaissements += $data['decaissements'];
                                    $solde = $data['encaissements'] - $data['decaissements'];
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data['name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($data['encaissements'], 2, ',', ' ') }} XAF
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($data['decaissements'], 2, ',', ' ') }} XAF
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="{{ $solde >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($solde, 2, ',', ' ') }} XAF
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-50 font-semibold">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($totalEncaissements, 2, ',', ' ') }} XAF
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($totalDecaissements, 2, ',', ' ') }} XAF
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="{{ ($totalEncaissements - $totalDecaissements) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($totalEncaissements - $totalDecaissements, 2, ',', ' ') }} XAF
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Graphical Representation -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Représentation Graphique</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Représentation graphique</h3>
                    <p class="mt-1 text-sm text-gray-500">Les graphiques seront disponibles dans une prochaine mise à jour.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection