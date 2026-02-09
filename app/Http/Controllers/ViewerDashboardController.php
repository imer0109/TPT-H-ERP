<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewerDashboardController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasPermission('viewer.dashboard') && !Auth::user()->hasRole('consultant') && !Auth::user()->hasRole('administrateur') && !Auth::user()->hasRole('admin') && !Auth::user()->hasRole('manager')) {
            abort(403, 'Accès non autorisé au tableau de bord consultant');
        }
        
        // Données factices pour le dashboard consultant
        $data = [
            'totalReports' => rand(5, 15),
            'activeProjects' => rand(3, 8),
            'completedProjects' => rand(10, 25),
            'pendingReviews' => rand(2, 6),
            'recentActivities' => [
                ['date' => now()->subDays(1)->format('d M Y'), 'activity' => 'Rapport mensuel consulté', 'user' => 'Manager'],
                ['date' => now()->subDays(2)->format('d M Y'), 'activity' => 'Analyse financière validée', 'user' => 'Comptabilité'],
                ['date' => now()->subDays(3)->format('d M Y'), 'activity' => 'Tableau de bord RH consulté', 'user' => 'RH'],
            ],
            'stats' => [
                ['label' => 'Ressources Humaines', 'value' => rand(20, 80), 'change' => rand(-5, 10)],
                ['label' => 'Finances', 'value' => rand(20, 80), 'change' => rand(-5, 10)],
                ['label' => 'Opérations', 'value' => rand(20, 80), 'change' => rand(-5, 10)],
                ['label' => 'Clients', 'value' => rand(20, 80), 'change' => rand(-5, 10)],
            ]
        ];

        return view('dashboards.viewer', compact('data'));
    }
}