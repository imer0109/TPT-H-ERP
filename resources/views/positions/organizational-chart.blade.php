@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Organigramme de l'Entreprise</h1>
        <div class="mt-3 sm:mt-0">
            <a href="{{ route('hr.positions.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="mdi mdi-arrow-left mr-1"></i> Retour à la gestion des postes
            </a>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Structure Organisationnelle</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Hiérarchie complète des postes de l'entreprise</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            @if($rootPositions->count() > 0)
                <div class="organizational-chart">
                    @foreach($rootPositions as $rootPosition)
                        @include('positions._chart-node', ['position' => $rootPosition])
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="mdi mdi-file-tree text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Aucun poste trouvé</h3>
                    <p class="text-gray-500">Commencez par créer des postes pour visualiser l'organigramme.</p>
                    <div class="mt-6">
                        <a href="{{ route('hr.positions.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="mdi mdi-plus-circle mr-2"></i> Créer un Poste
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Custom Styles for Organizational Chart -->
<style>
.organizational-chart {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px 0;
}

.chart-node {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 10px 0;
}

.node-content {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px 16px;
    min-width: 200px;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

.node-content:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-color: #d1d5db;
}

.node-title {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.node-department {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 4px;
}

.node-employees {
    font-size: 0.75rem;
    color: #9ca3af;
}

.node-children {
    display: flex;
    margin-top: 20px;
    position: relative;
}

.node-children::before {
    content: '';
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 2px;
    height: 10px;
    background: #e5e7eb;
}

.node-children > .chart-node {
    margin: 0 15px;
    position: relative;
}

.node-children > .chart-node::before {
    content: '';
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 2px;
    height: 10px;
    background: #e5e7eb;
}

.node-children > .chart-node::after {
    content: '';
    position: absolute;
    top: -10px;
    left: 0;
    width: 100%;
    height: 2px;
    background: #e5e7eb;
}

.node-children > .chart-node:first-child::after {
    width: 50%;
    left: 50%;
}

.node-children > .chart-node:last-child::after {
    width: 50%;
}

.node-actions {
    margin-top: 8px;
}

.node-actions a {
    color: #3b82f6;
    font-size: 0.875rem;
    text-decoration: none;
    margin: 0 4px;
}

.node-actions a:hover {
    text-decoration: underline;
}
</style>
@endsection