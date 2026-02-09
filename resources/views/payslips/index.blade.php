@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Gestion des Fiches de Paie</h2>
            <nav class="text-gray-500 text-sm mt-1">
                <ol class="flex space-x-2">
                    <li><a href="{{ route('dashboard') }}" class="hover:underline">Tableau de bord</a></li>
                    <li>/</li>
                    <li>Fiches de paie</li>
                </ol>
            </nav>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('hr.payslips.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md shadow">
                <i class="mdi mdi-plus-circle mr-2"></i> Générer Fiche de Paie
            </a>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="flex flex-col md:flex-row md:justify-between mb-4 space-y-4 md:space-y-0">
        <form method="GET" class="flex-1 md:mr-4 flex items-center space-x-2">
            <input type="text" name="search" placeholder="Rechercher un employé..." value="{{ request('search') }}" 
                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-primary-200 focus:outline-none">
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                <i class="mdi mdi-magnify"></i>
            </button>
        </form>

        <div class="flex space-x-2">
            <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="">Tous les statuts</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                <option value="validated" {{ request('status') == 'validated' ? 'selected' : '' }}>Validé</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payé</option>
            </select>

            <select name="month" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="">Tous les mois</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                @endfor
            </select>

            <select name="year" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="">Toutes les années</option>
                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Payslips Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Employé</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Période</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Salaire Brut</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Salaire Net</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Statut</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Date Génération</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-primary-700 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($payslips as $payslip)
                    <tr class="hover:bg-primary-50">
                        <td class="px-4 py-2 flex items-center space-x-2">
                            <img src="{{ $payslip->employee->photo ? asset('storage/' . $payslip->employee->photo) : asset('images/users/avatar-default.jpg') }}" 
                                 alt="Employee" class="w-8 h-8 rounded-full object-cover">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-800">{{ $payslip->employee->prenom }} {{ $payslip->employee->nom }}</span>
                                <span class="text-gray-500 text-sm">{{ $payslip->employee->currentPosition->title ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $payslip->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : ($payslip->status == 'validated' ? 'bg-primary-100 text-primary-800' : 'bg-green-100 text-green-800') }}">
                                {{ $payslip->month }}/{{ $payslip->year }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ number_format($payslip->gross_salary, 0, ',', ' ') }} FCFA</td>
                        <td class="px-4 py-2">{{ number_format($payslip->net_salary, 0, ',', ' ') }} FCFA</td>
                        <td class="px-4 py-2">
                            @if($payslip->status == 'draft')
                                <span class="px-2 inline-flex text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Brouillon</span>
                            @elseif($payslip->status == 'validated')
                                <span class="px-2 inline-flex text-xs font-semibold bg-primary-100 text-primary-800 rounded-full">Validé</span>
                            @elseif($payslip->status == 'paid')
                                <span class="px-2 inline-flex text-xs font-semibold bg-green-100 text-green-800 rounded-full">Payé</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $payslip->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-center">
                            <div class="relative inline-block text-left">
                                <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50" id="menu-button-{{ $payslip->id }}" aria-expanded="true" aria-haspopup="true">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden menu-{{ $payslip->id }}">
                                    <div class="py-1">
                                        <a href="{{ route('hr.payslips.show', $payslip) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Voir</a>
                                        <a href="{{ route('hr.payslips.download', $payslip) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Télécharger</a>
                                        @if($payslip->status == 'draft')
                                            <a href="{{ route('hr.payslips.edit', $payslip) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Modifier</a>
                                            <form action="{{ route('hr.payslips.validate', $payslip) }}" method="POST" class="block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Valider</button>
                                            </form>
                                        @endif
                                        @if($payslip->status == 'validated')
                                            <form action="{{ route('hr.payslips.pay', $payslip) }}" method="POST" class="block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Marquer comme Payé</button>
                                            </form>
                                        @endif
                                        @if($payslip->status == 'draft')
                                            <form action="{{ route('hr.payslips.destroy', $payslip) }}" method="POST" class="block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette fiche de paie ?')">Supprimer</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8">
                            <img src="{{ asset('images/undraw_empty.svg') }}" alt="Aucune donnée" class="mx-auto mb-4" style="max-height: 200px;">
                            <h4 class="text-lg font-medium text-gray-700">Aucune fiche de paie trouvée</h4>
                            <p class="text-gray-500 mb-4">Commencez par générer une fiche de paie pour vos employés.</p>
                            <a href="{{ route('hr.payslips.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">Générer une Fiche de Paie</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $payslips->links() }}
    </div>
</div>
@endsection
