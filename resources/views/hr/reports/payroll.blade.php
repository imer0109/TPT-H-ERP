@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-6 space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Rapport de Paie ({{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }})
            </h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a href="{{ route('dashboard') }}" class="hover:text-primary-600">Tableau de bord</a>
                <span class="mx-2">/</span>
                <span class="text-gray-700 font-medium">Rapport de paie</span>
            </nav>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow p-6">
        <form method="GET" action="{{ route('hr.reports.payroll') }}"
              class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            <div>
                <label class="text-sm font-medium text-gray-600">Mois</label>
                <select name="month" class="w-full mt-1 rounded-lg border-gray-300 focus:ring-primary-500">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Année</label>
                <select name="year" class="w-full mt-1 rounded-lg border-gray-300 focus:ring-primary-500">
                    @for($y = date('Y') - 5; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="flex gap-2 md:col-span-2">
                <button class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-lg">
                    🔍 Filtrer
                </button>
                <a href="{{ route('hr.reports.payroll') }}"
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg text-center">
                    🔄 Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Summary cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-primary-500">
            <p class="text-sm text-gray-500">Employés payés</p>
            <h2 class="text-3xl font-bold text-primary-600 mt-2">0</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Salaire brut total</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">0.00 FCFA</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Charges sociales</p>
            <h2 class="text-3xl font-bold text-yellow-500 mt-2">0.00 FCFA</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-indigo-500">
            <p class="text-sm text-gray-500">Salaire net total</p>
            <h2 class="text-3xl font-bold text-indigo-600 mt-2">0.00 FCFA</h2>
        </div>

    </div>

    <!-- Payroll analysis -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Table -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Analyse par département</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="text-left p-2">Département</th>
                            <th class="text-right p-2">Effectif</th>
                            <th class="text-right p-2">Brut</th>
                            <th class="text-right p-2">Charges</th>
                            <th class="text-right p-2">Net</th>
                            <th class="text-right p-2">Coût</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach(['Direction','RH','Commercial','Logistique'] as $dept)
                        <tr>
                            <td class="p-2">{{ $dept }}</td>
                            <td class="p-2 text-right">0</td>
                            <td class="p-2 text-right">0.00</td>
                            <td class="p-2 text-right">0.00 FCFA</td>
                            <td class="p-2 text-right">0.00 FCFA</td>
                            <td class="p-2 text-right">0.00 FCFA</td>
                        </tr>
                        @endforeach
                        <tr class="font-bold bg-gray-50">
                            <td class="p-2">TOTAL</td>
                            <td class="p-2 text-right">0</td>
                            <td class="p-2 text-right">0.00</td>
                            <td class="p-2 text-right">0.00 FCFA</td>
                            <td class="p-2 text-right">0.00 FCFA</td>
                            <td class="p-2 text-right">0.00 FCFA</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Charges -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Répartition des charges</h3>
            <div id="payroll-charges-chart"></div>

            <div class="mt-6 space-y-3 text-sm">
                <div>
                    <div class="flex justify-between">
                        <span>Sécurité sociale</span><span>0.00 FCFA</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded">
                        <div class="h-2 bg-primary-500 rounded w-[60%]"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between">
                        <span>Assurance chômage</span><span>0.00 FCFA</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded">
                        <div class="h-2 bg-green-500 rounded w-[25%]"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between">
                        <span>Retraite</span><span>0.00 FCFA</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded">
                        <div class="h-2 bg-yellow-500 rounded w-[15%]"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Trend -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4">
            Évolution de la masse salariale (12 mois)
        </h3>
        <div id="payroll-trend-chart"></div>
    </div>

    <!-- Actions -->
    <div class="flex flex-wrap justify-end gap-3">
        <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">📄 PDF</button>
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">📊 Excel</button>
        <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">✉️ Email</button>
    </div>

</div>
@endsection
