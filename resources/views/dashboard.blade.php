@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Trésorerie -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-wallet text-red-500 text-2xl"></i>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Trésorerie consolidée</h3>
                        <p class="text-2xl font-semibold text-gray-900">2,450,000 FCFA</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Masse salariale -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-users text-blue-500 text-2xl"></i>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Masse salariale</h3>
                        <p class="text-2xl font-semibold text-gray-900">1,200,000 FCFA</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stocks -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-boxes text-green-500 text-2xl"></i>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Stocks disponibles</h3>
                        <p class="text-2xl font-semibold text-gray-900">324 articles</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achats -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-shopping-cart text-yellow-500 text-2xl"></i>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Achats mensuels</h3>
                        <p class="text-2xl font-semibold text-gray-900">850,000 FCFA</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Line Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Encaissements/Dépenses</h3>
            <div class="h-64">
                <canvas id="revenueChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Répartition par pôle d'activité</h3>
            <div class="h-64">
                <canvas id="activityChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Alertes & Notifications</h3>
        </div>
        <div class="divide-y divide-gray-200">
            <div class="px-6 py-4 flex items-center">
                <span class="flex-shrink-0 w-2 h-2 rounded-full bg-red-600 mr-3"></span>
                <p class="text-sm text-gray-600">Stock bas : Cartouches d'encre (5 unités restantes)</p>
            </div>
            <div class="px-6 py-4 flex items-center">
                <span class="flex-shrink-0 w-2 h-2 rounded-full bg-yellow-500 mr-3"></span>
                <p class="text-sm text-gray-600">Demande d'achat en attente de validation</p>
            </div>
            <div class="px-6 py-4 flex items-center">
                <span class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mr-3"></span>
                <p class="text-sm text-gray-600">3 absences à valider pour la semaine en cours</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
        datasets: [{
            label: 'Encaissements',
            data: [1200000, 1900000, 1500000, 2100000, 1800000, 2400000],
            borderColor: '#C20000',
            backgroundColor: 'rgba(194,0,0,0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Dépenses',
            data: [900000, 1200000, 1100000, 1500000, 1300000, 1800000],
            borderColor: '#666',
            backgroundColor: 'rgba(102,102,102,0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
            legend: { position: 'bottom' }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Activity Chart
const activityCtx = document.getElementById('activityChart').getContext('2d');
new Chart(activityCtx, {
    type: 'pie',
    data: {
        labels: ['Commercial', 'Services', 'Production', 'Administration'],
        datasets: [{
            data: [30, 25, 25, 20],
            backgroundColor: ['#C20000', '#FF6B6B', '#4A90E2', '#50E3C2']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});
</script>
@endsection
