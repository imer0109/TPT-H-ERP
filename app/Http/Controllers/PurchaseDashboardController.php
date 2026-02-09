<?php

namespace App\Http\Controllers;

class PurchaseDashboardController
{
    public function index()
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
            'recentRequests' => [
                ['id' => 1, 'reference' => 'DA-001', 'statut' => 'en_attente', 'created_at' => date('d M Y')],
                ['id' => 2, 'reference' => 'DA-002', 'statut' => 'approuvee', 'created_at' => date('d M Y', strtotime('-1 day'))],
            ],
            'recentOrders' => [
                ['id' => 1, 'reference' => 'BC-001', 'statut' => 'pending', 'fournisseur' => 'Tech Supply'],
                ['id' => 2, 'reference' => 'BC-002', 'statut' => 'completed', 'fournisseur' => 'Office Pro'],
            ]
        ];

        // Retourner une réponse JSON simple pour le moment
        return response()->json([
            'status' => 'success',
            'message' => 'Dashboard achats accessible',
            'data' => $data
        ]);
    }
}
