<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierDashboardController extends Controller
{
    /**
     * Display the supplier dashboard.
     */
    public function index()
    {
        // Check if user has permission to view supplier dashboard
        if (!Auth::user()->hasPermission('suppliers.dashboard') && !Auth::user()->hasRole('supplier') && !Auth::user()->hasRole('administrateur') && !Auth::user()->hasRole('admin') && !Auth::user()->hasRole('manager')) {
            abort(403, 'Accès non autorisé au tableau de bord fournisseur');
        }

        // Mock data for supplier dashboard
        $totalOrders = 12;
        $pendingOrders = 3;
        $completedOrders = 8;
        
        $totalDeliveries = 15;
        $pendingDeliveries = 2;
        $receivedDeliveries = 12;
        
        $totalPayments = 8;
        $pendingPayments = 1;
        $paidPayments = 7;
        
        $totalIssues = 2;
        $openIssues = 1;
        $resolvedIssues = 1;
        
        // Recent activities (mock data)
        $recentOrders = collect([
            (object)['id' => 1, 'reference' => 'BC-001', 'statut' => 'confirmed', 'montant_total' => 1500000, 'created_at' => now()],
            (object)['id' => 2, 'reference' => 'BC-002', 'statut' => 'pending', 'montant_total' => 800000, 'created_at' => now()->subDay()],
        ]);
        
        $recentDeliveries = collect([
            (object)['id' => 1, 'numero_bl' => 'BL-001', 'statut' => 'received', 'date_reception' => now()],
            (object)['id' => 2, 'numero_bl' => 'BL-002', 'statut' => 'pending', 'date_reception' => now()->addDay()],
        ]);
        
        $recentPayments = collect([
            (object)['id' => 1, 'reference' => 'PAY-001', 'montant' => 1200000, 'statut' => 'paid', 'date_paiement' => now()],
            (object)['id' => 2, 'reference' => 'PAY-002', 'montant' => 800000, 'statut' => 'pending', 'date_paiement' => now()->addWeek()],
        ]);
        
        // Performance metrics
        $performanceMetrics = [
            'delivery_rate' => 92,
            'quality_score' => 4.2,
            'response_time' => '24h',
            'certification_status' => 'Active'
        ];
        
        // Chart data for monthly overview
        $chartData = [
            'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            'datasets' => [
                [
                    'label' => 'Commandes',
                    'data' => [5, 8, 6, 9, 7, 12],
                    'borderColor' => '#dc2626',
                    'backgroundColor' => 'rgba(220, 38, 38, 0.1)',
                    'fill' => true,
                    'tension' => 0.4
                ],
                [
                    'label' => 'Livraisons',
                    'data' => [4, 7, 5, 8, 6, 10],
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4
                ]
            ]
        ];

        return view('suppliers.dashboard', compact(
            'totalOrders',
            'pendingOrders', 
            'completedOrders',
            'totalDeliveries',
            'pendingDeliveries',
            'receivedDeliveries',
            'totalPayments',
            'pendingPayments',
            'paidPayments',
            'totalIssues',
            'openIssues',
            'resolvedIssues',
            'recentOrders',
            'recentDeliveries',
            'recentPayments',
            'performanceMetrics',
            'chartData'
        ));
    }
}