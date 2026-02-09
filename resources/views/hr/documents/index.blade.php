@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Documents RH</h1>
            <p class="mt-1 text-sm text-gray-500">Gérez les documents liés aux employés</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button onclick="window.location.href='{{ route('hr.documents.work-certificate', ['employee' => auth()->user()->employee ?? 1]) }}'" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                </svg>
                Certificat de Travail
            </button>
        </div>
    </div>

    <!-- Documents Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Work Certificate Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Certificat de Travail</h3>
                        <p class="mt-1 text-sm text-gray-500">Générer un certificat de travail pour un employé</p>
                    </div>
                </div>
                <div class="mt-4">
                    <button onclick="window.location.href='{{ route('hr.documents.work-certificate', ['employee' => auth()->user()->employee ?? 1]) }}'" 
                            class="text-sm font-medium text-red-600 hover:text-red-500">
                        Accéder
                    </button>
                </div>
            </div>
        </div>

        <!-- Salary Certificate Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Certificat de Salaire</h3>
                        <p class="mt-1 text-sm text-gray-500">Générer un certificat de salaire pour un employé</p>
                    </div>
                </div>
                <div class="mt-4">
                    <button onclick="window.location.href='{{ route('hr.documents.salary-certificate', ['employee' => auth()->user()->employee ?? 1]) }}'" 
                            class="text-sm font-medium text-green-600 hover:text-green-500">
                        Accéder
                    </button>
                </div>
            </div>
        </div>

        <!-- Other Documents Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-primary-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Autres Documents</h3>
                        <p class="mt-1 text-sm text-gray-500">Gérer d'autres documents RH</p>
                    </div>
                </div>
                <div class="mt-4">
                    <button onclick="alert('Fonctionnalité à venir')" 
                            class="text-sm font-medium text-primary-600 hover:text-primary-500">
                        Accéder
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Documents Section -->
    <div class="mt-8">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Documents Récents</h2>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            @if(isset($recentDocuments) && $recentDocuments->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($recentDocuments as $document)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium truncate">
                                        @switch($document->type_document)
                                            @case('contrat')
                                                <span class="text-primary-600">{{ $document->nom }}</span>
                                                @break
                                            @case('bon_commande')
                                                <span class="text-green-600">{{ $document->nom }}</span>
                                                @break
                                            @case('fiche_ouverture')
                                                <span class="text-purple-600">{{ $document->nom }}</span>
                                                @break
                                            @case('rccm')
                                                <span class="text-yellow-600">{{ $document->nom }}</span>
                                                @break
                                            @case('niu')
                                                <span class="text-indigo-600">{{ $document->nom }}</span>
                                                @break
                                            @case('piece_identite')
                                                <span class="text-pink-600">{{ $document->nom }}</span>
                                                @break
                                            @default
                                                <span class="text-gray-600">{{ $document->nom }}</span>
                                        @endswitch
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Généré</p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $document->type_document }}
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        <p>{{ $document->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-4 py-8 sm:px-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun document</h3>
                    <p class="mt-1 text-sm text-gray-500">Aucun document n'a été généré pour le moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection