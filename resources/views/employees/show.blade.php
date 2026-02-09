@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    {{-- GRID PRINCIPALE --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- SIDEBAR PROFIL --}}
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex flex-col items-center">

                <img src="{{ $employee->photo ? asset('storage/' . $employee->photo) : asset('img/default-avatar.png') }}"
                     class="w-28 h-28 rounded-full object-cover border">

                <h2 class="mt-4 text-xl font-semibold">
                    {{ $employee->last_name }} {{ $employee->first_name }}
                </h2>

                <p class="text-gray-500 text-sm">
                    {{ $employee->currentPosition->title ?? 'Aucun poste' }}
                </p>

                <div class="w-full mt-6 space-y-2">
                    <div class="flex justify-between text-sm border-b pb-2">
                        <span class="font-medium">Matricule</span>
                        <span>{{ $employee->matricule }}</span>
                    </div>

                    <div class="flex justify-between text-sm border-b pb-2">
                        <span class="font-medium">Email</span>
                        <span>{{ $employee->email }}</span>
                    </div>

                    <div class="flex justify-between text-sm border-b pb-2">
                        <span class="font-medium">Téléphone</span>
                        <span>{{ $employee->phone ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between text-sm border-b pb-2">
                        <span class="font-medium">Statut</span>

                        <span class="px-2 py-1 rounded text-white text-xs
                            {{ $employee->status === 'actif' ? 'bg-green-600' : 'bg-red-600' }}">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="flex gap-2 mt-6 w-full">
                    <a href="{{ route('hr.employees.edit', $employee) }}"
                       class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded">
                        Modifier
                    </a>

                    <button onclick="confirmDelete('{{ $employee->id }}')"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded">
                        Supprimer
                    </button>
                </div>

            </div>
        </div>

        {{-- CONTENU PRINCIPAL --}}
        <div class="lg:col-span-3 bg-white shadow rounded-lg p-6">

            {{-- TABS NAV --}}
            <ul class="flex border-b mb-6 space-x-4 text-sm font-medium">
                <li><a href="#details" class="tab-link active-tab">Détails</a></li>
                <li><a href="#contracts" class="tab-link">Contrats</a></li>
                <li><a href="#leaves" class="tab-link">Congés</a></li>
                <li><a href="#attendances" class="tab-link">Présences</a></li>
                <li><a href="#evaluations" class="tab-link">Évaluations</a></li>
                <li><a href="#assignments" class="tab-link">Affectations</a></li>
            </ul>

            {{-- NAVIGATION 1–6 --}}
            <div class="flex flex-wrap gap-3 mb-6">

                {{-- 1. Contrats --}}
                <a href="{{ route('hr.contracts.index') }}"
                   class="px-5 py-2 rounded-lg font-semibold transition
                   {{ request()->routeIs('hr.contracts.*')
                        ? 'bg-primary-600 text-white shadow-lg'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Contrats
                </a>

                {{-- 2. Congés --}}
                <a href="{{ route('hr.leaves.index') }}"
                   class="px-5 py-2 rounded-lg font-semibold transition
                   {{ request()->routeIs('hr.leaves.*')
                        ? 'bg-primary-600 text-white shadow-lg'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Congés
                </a>

                {{-- 3. Présences --}}
                <a href="{{ route('hr.attendances.index') }}"
                   class="px-5 py-2 rounded-lg font-semibold transition
                   {{ request()->routeIs('hr.attendances.*')
                        ? 'bg-primary-600 text-white shadow-lg'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Présences
                </a>

                {{-- 4. Évaluations --}}
                <a href="{{ route('hr.evaluations.index') }}"
                   class="px-5 py-2 rounded-lg font-semibold transition
                   {{ request()->routeIs('hr.evaluations.*')
                        ? 'bg-primary-600 text-white shadow-lg'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Évaluations
                </a>

                {{-- 5. Affectations --}}
                <a href="#assignments"
                   class="px-5 py-2 rounded-lg font-semibold transition
                   {{ request()->routeIs('hr.employees.assignments.*')
                        ? 'bg-primary-600 text-white shadow-lg'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                   onclick="document.querySelector('a[href=\'#assignments\']').click();">
                    Affectations
                </a>

                {{-- 6. Autres --}}
                <a href="#"
                   class="px-5 py-2 rounded-lg font-semibold transition
                   {{ request()->routeIs('autre.*')
                        ? 'bg-primary-600 text-white shadow-lg'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Autres
                </a>

            </div>

            {{-- CONTENU DES TABS --}}
            <div id="details" class="tab-content block">
                <h3 class="text-lg font-semibold mb-4">Informations Personnelles</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <table class="w-full text-sm">
                        <tr class="border-b">
                            <th class="py-2 font-medium">Date de naissance</th>
                            <td>{{ $employee->birth_date ? $employee->birth_date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 font-medium">Lieu de naissance</th>
                            <td>{{ $employee->birth_place ?? 'N/A' }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 font-medium">Nationalité</th>
                            <td>{{ $employee->nationality ?? 'N/A' }}</td>
                        </tr>
                    </table>

                    <table class="w-full text-sm">
                        <tr class="border-b">
                            <th class="py-2 font-medium">Poste actuel</th>
                            <td>{{ $employee->currentPosition->title ?? 'N/A' }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 font-medium">Date d'embauche</th>
                            <td>{{ $employee->getDateEmbaucheAttribute()?->format('d/m/Y') ?? 'N/A' }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 font-medium">Superviseur</th>
                            <td>
                                @if($employee->supervisor)
                                    {{ $employee->supervisor->last_name }} {{ $employee->supervisor->first_name }}
                                @else
                                    Non assigné
                                @endif
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

        </div>

    </div>
</div>

{{-- JS simple pour tabs --}}
<script>
    const links = document.querySelectorAll('.tab-link');
    const contents = document.querySelectorAll('.tab-content');

    links.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();

            links.forEach(l => l.classList.remove('active-tab'));
            link.classList.add('active-tab');

            contents.forEach(c => c.classList.add('hidden'));
            const target = document.querySelector(link.getAttribute('href'));
            target.classList.remove('hidden');
        });
    });
</script>

{{-- Styles Tailwind personnalisés --}}
<style>
    .tab-link { @apply pb-2 border-b-2 border-transparent text-gray-500 hover:text-gray-800; }
    .active-tab { @apply text-primary-600 border-primary-600; }
    .tab-content { @apply hidden; }
    .tab-content.block { @apply block; }
</style>

@endsection
