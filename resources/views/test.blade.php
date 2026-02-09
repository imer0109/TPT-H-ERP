@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Test Blade OK</h1>
                <div class="space-y-4">
                    <p class="text-gray-600">Date: <span class="font-medium text-gray-900">{{ date('Y-m-d H:i:s') }}</span></p>
                    <p class="text-gray-600">Page de test pour vérifier le fonctionnement de Blade et du système de design.</p>
                    
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Composants UI Test</h2>
                        <div class="flex space-x-4 items-center">
                            <x-button>Bouton Primaire</x-button>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                Badge Primary
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
