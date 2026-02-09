@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Évaluations</h1>

        <a href="{{ route('hr.evaluations.create') }}"
           class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-primary-600 text-white rounded-lg shadow hover:bg-primary-700 transition">
            <i class="mdi mdi-plus-circle mr-2 text-lg"></i>
            Nouvelle Évaluation
        </a>
    </div>

    <!-- FILTERS -->
    <div class="bg-white p-5 rounded-lg shadow mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Search -->
            <input type="text" name="search" placeholder="Rechercher un employé..."
                   value="{{ request('search') }}"
                   class="col-span-1 md:col-span-4 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-400">

            <!-- Status -->
            <select name="status" onchange="this.form.submit()"
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-400">
                <option value="">Tous les statuts</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Soumise</option>
                <option value="acknowledged" {{ request('status') == 'acknowledged' ? 'selected' : '' }}>Reconnue</option>
                <option value="disputed" {{ request('status') == 'disputed' ? 'selected' : '' }}>Contestée</option>
            </select>

            <!-- Period -->
            <select name="period" onchange="this.form.submit()"
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-400">
                <option value="">Toutes les périodes</option>
                <option value="Q1" {{ request('period') == 'Q1' ? 'selected' : '' }}>T1 {{ date('Y') }}</option>
                <option value="Q2" {{ request('period') == 'Q2' ? 'selected' : '' }}>T2 {{ date('Y') }}</option>
                <option value="Q3" {{ request('period') == 'Q3' ? 'selected' : '' }}>T3 {{ date('Y') }}</option>
                <option value="Q4" {{ request('period') == 'Q4' ? 'selected' : '' }}>T4 {{ date('Y') }}</option>
            </select>

            <!-- Year -->
            <select name="year" onchange="this.form.submit()"
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-400">
                <option value="">Toutes les années</option>
                @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Employé</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Période</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Type</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-700">Note Globale</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Statut</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Créée le</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($evaluations as $evaluation)
                <tr class="hover:bg-primary-50 transition">
                    <!-- Employee -->
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">
                            {{ $evaluation->employee->full_name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $evaluation->employee->currentPosition->title ?? 'N/A' }}
                        </div>
                    </td>

                    <!-- Period -->
                    <td class="px-6 py-4 text-gray-700">
                        {{ $evaluation->period }}
                    </td>

                    <!-- Evaluation Type -->
                    <td class="px-6 py-4 text-gray-700">
                        {{ $evaluation->evaluation_type_text }}
                    </td>

                    <!-- Score -->
                    <td class="px-6 py-4 text-right">
                        @if($evaluation->overall_score)
                        <span class="px-3 py-1 text-xs rounded-full text-white bg-{{ $evaluation->overall_rating_color }}-600">
                            {{ $evaluation->overall_score }}/5 – {{ $evaluation->overall_rating_text }}
                        </span>
                        @else
                        <span class="text-gray-500">Non évalué</span>
                        @endif
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-{{ $evaluation->status_color }}-100 text-{{ $evaluation->status_color }}-700">
                            {{ $evaluation->status_text }}
                        </span>
                    </td>

                    <!-- Date -->
                    <td class="px-6 py-4 text-gray-700">
                        {{ $evaluation->created_at->format('d/m/Y') }}
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center items-center space-x-2">
                            <!-- View Button (Always visible) -->
                            <a href="{{ route('hr.evaluations.show', $evaluation) }}"
                               class="text-primary-600 hover:text-primary-800 text-lg p-1"
                               title="Voir">
                                <i class="mdi mdi-eye"></i>
                            </a>

                            <!-- Edit Button (Only for draft evaluations) -->
                            @if($evaluation->isDraft())
                            <a href="{{ route('hr.evaluations.edit', $evaluation) }}"
                               class="text-indigo-600 hover:text-indigo-800 text-lg p-1"
                               title="Modifier">
                                <i class="mdi mdi-pencil"></i>
                            </a>

                            <!-- Delete Button (Only for draft evaluations) -->
                            <form action="{{ route('hr.evaluations.destroy', $evaluation) }}" 
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Supprimer cette évaluation ?')" 
                                        class="text-red-600 hover:text-red-800 text-lg p-1"
                                        title="Supprimer">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                            @endif

                            <!-- Acknowledge Button (Only for submitted evaluations) -->
                            @if($evaluation->isSubmitted())
                            <form action="{{ route('hr.evaluations.acknowledge', $evaluation) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 text-lg p-1" title="Confirmer">
                                    <i class="mdi mdi-check-circle"></i>
                                </button>
                            </form>
                            @endif

                            <!-- Dispute Button (Only for submitted evaluations) -->
                            @if($evaluation->isSubmitted())
                            <form action="{{ route('hr.evaluations.dispute', $evaluation) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-orange-600 hover:text-orange-800 text-lg p-1" title="Contester">
                                    <i class="mdi mdi-alert-circle"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center">
                        <img src="{{ asset('images/undraw_empty.svg') }}" class="mx-auto mb-4 w-48">
                        <h3 class="text-lg font-semibold text-gray-800">Aucune évaluation trouvée</h3>
                        <p class="text-gray-500 mb-4">Commencez par créer une nouvelle évaluation.</p>
                        <a href="{{ route('hr.evaluations.create') }}"
                           class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
                            Créer une Évaluation
                        </a>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $evaluations->links() }}
    </div>

</div>
@endsection