<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class PurchaseDashboardController
{
    public function index(Request $request)
    {
        // Données factices pour le dashboard achats
        $data = [
            'totalRequests' => rand(20, 30),
            'pendingRequests' => rand(5, 10),
            'approvedRequests' => rand(10, 20),
            'totalOrders' => rand(15, 25),
            'pendingOrders' => rand(3, 8),
            'completedOrders' => rand(8, 18),
            'totalSuppliers' => rand(12, 18),
            'activeSuppliers' => rand(10, 15),
            'lowStockItems' => rand(2, 6),
            // Données pour les statistiques
            'stats' => [
                'total_requests' => rand(20, 30),
                'pending_requests' => rand(5, 10),
                'total_orders' => rand(15, 25),
                'confirmed_orders' => rand(8, 15),
                'delivered_orders' => rand(5, 12),
                'total_amount' => rand(5000000, 15000000),
                'monthly_amount' => rand(1000000, 5000000),
            ],
            'recentRequests' => [
                ['id' => 1, 'reference' => 'DA-001', 'statut' => 'en_attente', 'created_at' => date('d M Y')],
                ['id' => 2, 'reference' => 'DA-002', 'statut' => 'approuvee', 'created_at' => date('d M Y', strtotime('-1 day'))],
            ],
            'recent_orders' => collect([
                (object) [
                    'id' => 1, 
                    'reference' => 'BC-001', 
                    'statut' => 'pending', 
                    'fournisseur' => (object) ['nom' => 'Tech Supply'], 
                    'code' => 'BC-001', 
                    'montant_ttc' => 1500000, 
                    'created_at' => now()
                ],
                (object) [
                    'id' => 2, 
                    'reference' => 'BC-002', 
                    'statut' => 'completed', 
                    'fournisseur' => (object) ['nom' => 'Office Pro'], 
                    'code' => 'BC-002', 
                    'montant_ttc' => 2300000, 
                    'created_at' => now()->subDay()
                ]
            ]),
            'top_suppliers' => collect([
                (object) [
                    'fournisseur' => (object) ['nom' => 'Tech Supply'],
                    'order_count' => rand(5, 15),
                    'total_amount' => rand(2000000, 8000000)
                ],
                (object) [
                    'fournisseur' => (object) ['nom' => 'Office Pro'],
                    'order_count' => rand(3, 10),
                    'total_amount' => rand(1500000, 6000000)
                ],
                (object) [
                    'fournisseur' => (object) ['nom' => 'Global Solutions'],
                    'order_count' => rand(2, 8),
                    'total_amount' => rand(1000000, 4000000)
                ]
            ]),
            'chartData' => [
                'monthly' => [
                    'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                    'data' => [rand(1000000, 3000000), rand(1500000, 4000000), rand(2000000, 5000000), rand(1800000, 4500000), rand(2200000, 5200000), rand(2500000, 6000000)]
                ]
            ],
            'alerts' => [
                'overdue_orders' => rand(0, 5)
            ]
        ];

        // Rendre la vue Blade du dashboard achats
        return view('purchases.dashboard', $data);
    }
}
