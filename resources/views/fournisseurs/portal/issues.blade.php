@extends('fournisseurs.portal.layout')

@section('title', 'Réclamations Fournisseur')
@section('header', 'Réclamations')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="mx-auto max-w-7xl px-4 space-y-6">

        <!-- HEADER -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl font-semibold text-gray-800">Réclamations</h2>

            <a href="{{ route('supplier.portal.create-issue') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-primary-700">
                <i class="fas fa-plus"></i>
                Nouvelle réclamation
            </a>
        </div>

        <!-- FILTRES -->
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('supplier.portal.issues') }}"
                  class="flex flex-col gap-3 md:flex-row md:items-end">

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">Statut</label>
                    <select name="status" class="input">
                        <option value="">Tous</option>
                        <option value="open" @selected(request('status') === 'open')>Ouverte</option>
                        <option value="in_progress" @selected(request('status') === 'in_progress')>En cours</option>
                        <option value="resolved" @selected(request('status') === 'resolved')>Résolue</option>
                        <option value="closed" @selected(request('status') === 'closed')>Fermée</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">Type</label>
                    <select name="type" class="input">
                        <option value="">Tous</option>
                        <option value="retard" @selected(request('type') === 'retard')>Retard</option>
                        <option value="produit_non_conforme" @selected(request('type') === 'produit_non_conforme')>Produit non conforme</option>
                        <option value="erreur_facturation" @selected(request('type') === 'erreur_facturation')>Erreur de facturation</option>
                        <option value="autre" @selected(request('type') === 'autre')>Autre</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button class="btn-secondary">
                        <i class="fas fa-filter mr-1"></i> Filtrer
                    </button>

                    <a href="{{ route('supplier.portal.issues') }}" class="btn-light">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="rounded-xl bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100 text-xs uppercase text-primary-700">
                        <tr>
                            <th class="px-6 py-3 text-left">Titre</th>
                            <th class="px-6 py-3 text-left">Type</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Statut</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($issues as $issue)
                            <tr class="hover:bg-primary-50">
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $issue->titre }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ ucfirst(str_replace('_', ' ', $issue->type)) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $issue->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'open' => 'bg-primary-100 text-primary-700',
                                            'in_progress' => 'bg-yellow-100 text-yellow-700',
                                            'resolved' => 'bg-green-100 text-green-700',
                                            'closed' => 'bg-gray-200 text-gray-700',
                                        ];
                                    @endphp

                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $statusClasses[$issue->statut] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst(str_replace('_', ' ', $issue->statut)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('supplier.portal.issues.show', $issue) }}"
                                       class="text-primary-600 hover:underline text-sm font-medium">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500">
                                    Aucune réclamation trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="border-t bg-gray-50 px-6 py-3">
                {{ $issues->withQueryString()->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
