<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountingDashboardController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasPermission('accounting.dashboard') && !Auth::user()->hasRole('accounting') && !Auth::user()->hasRole('administrateur') && !Auth::user()->hasRole('admin') && !Auth::user()->hasRole('manager')) {
            abort(403, 'Accès non autorisé au tableau de bord comptabilité');
        }
        
        // Données factices pour le dashboard comptabilité
        $data = [
            'totalRevenue' => rand(5000000, 10000000),
            'totalExpenses' => rand(3000000, 6000000),
            'netProfit' => rand(1000000, 4000000),
            'pendingInvoices' => rand(5, 15),
            'overdueInvoices' => rand(2, 8),
            'recentTransactions' => [
                ['date' => now()->subDays(1)->format('d M Y'), 'description' => 'Paiement client', 'amount' => 150000, 'type' => 'income'],
                ['date' => now()->subDays(2)->format('d M Y'), 'description' => 'Facture fournisseur', 'amount' => 85000, 'type' => 'expense'],
                ['date' => now()->subDays(3)->format('d M Y'), 'description' => 'Salaire employés', 'amount' => 2500000, 'type' => 'expense'],
            ],
            'accountBalances' => [
                ['account' => 'Compte Bancaire Principal', 'balance' => rand(1000000, 3000000)],
                ['account' => 'Compte Courant', 'balance' => rand(500000, 1500000)],
                ['account' => 'Caisse', 'balance' => rand(200000, 500000)],
            ]
        ];

        return view('dashboards.accounting', compact('data'));
    }
}