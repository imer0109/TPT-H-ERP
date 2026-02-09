@extends('layouts.app')

@section('title', 'Tableau de bord Achats')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de bord Achats</h1>
        <p class="text-gray-600 mt-2">Vue d'ensemble des activités d'achat</p>
    </div>

    <!-- Purchases Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-primary-500">
            <h3 class="text-gray-500 text-sm font-medium">Demandes d'achat</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalRequests }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $pendingRequests }} en attente</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
            <h3 class="text-gray-500 text-sm font-medium">Bons de commande</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalOrders }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $completedOrders }} complétés</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-medium">Fournisseurs</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalSuppliers }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $activeSuppliers }} actifs</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm font-medium">Articles Stock Bas</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $lowStockItems }}</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Demandes vs Commandes</h3>
            <div class="flex items-center justify-center h-64">
                <div class="relative w-48 h-48">
                    <svg class="w-full h-full" viewBox="0 0 100 100">
                        <!-- Circle chart will be rendered here by JavaScript -->
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#e5e7eb" stroke-width="8"></circle>
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#8b5cf6" stroke-width="8" 
                                stroke-dasharray="283" stroke-dashoffset="141" transform="rotate(-90 50 50)" stroke-linecap="round"></circle>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex justify-center space-x-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">Demandes</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">Commandes</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Récent</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-primary-50 rounded">
                    <span class="font-medium">Demandes récentes</span>
                    <span class="bg-primary-100 text-primary-800 px-2 py-1 rounded-full text-sm">{{ count($recentRequests) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded">
                    <span class="font-medium">Commandes récentes</span>
                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-sm">{{ count($recentOrders) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Requests -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Demandes Récentes</h3>
            <div class="space-y-4">
                @forelse($recentRequests as $request)
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition">
                    <div>
                        <p class="font-medium text-gray-800">{{ $request->reference }}</p>
                        <p class="text-xs text-gray-500">{{ $request->created_at->format('d M Y') }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                        {{ $request->statut }}
                    </span>
                </div>
                @empty
                <p class="text-center text-sm text-gray-500 py-4">Aucune demande récente</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Commandes Récentes</h3>
            <div class="space-y-4">
                @forelse($recentOrders as $order)
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition">
                    <div>
                        <p class="font-medium text-gray-800">{{ $order->reference }}</p>
                        <p class="text-xs text-gray-500">Fournisseur</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                        {{ $order->statut }}
                    </span>
                </div>
                @empty
                <p class="text-center text-sm text-gray-500 py-4">Aucune commande récente</p>
                @endforelse
            </div>
        </div>

        <!-- Suppliers -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Fournisseurs</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium">Total</span>
                    <span class="font-medium">{{ $totalSuppliers }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded">
                    <span class="font-medium">Actifs</span>
                    <span class="font-medium">{{ $activeSuppliers }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-yellow-50 rounded">
                    <span class="font-medium">En attente</span>
                    <span class="font-medium">{{ $totalSuppliers - $activeSuppliers }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection