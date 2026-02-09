<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationalAgentDashboardController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasPermission('operational.dashboard') && !Auth::user()->hasRole('operational') && !Auth::user()->hasRole('agent_operationnel') && !Auth::user()->hasRole('administrateur') && !Auth::user()->hasRole('admin') && !Auth::user()->hasRole('manager')) {
            abort(403, 'Accès non autorisé au tableau de bord agent opérationnel');
        }
        
        // Données factices pour le dashboard agent opérationnel
        $data = [
            'totalTasks' => rand(15, 30),
            'completedTasks' => rand(10, 25),
            'pendingTasks' => rand(3, 8),
            'assignedToday' => rand(2, 5),
            'performanceScore' => rand(75, 95),
            'recentActivities' => [
                ['date' => now()->subHours(2)->format('d M Y H:i'), 'activity' => 'Traitement d\'une commande client', 'status' => 'completed'],
                ['date' => now()->subHours(4)->format('d M Y H:i'), 'activity' => 'Suivi livraison', 'status' => 'in_progress'],
                ['date' => now()->subDay()->format('d M Y H:i'), 'activity' => 'Rapport quotidien soumis', 'status' => 'completed'],
            ],
            'upcomingTasks' => [
                ['task' => 'Livraison à effectuer', 'priority' => 'high', 'deadline' => now()->addDay()->format('d M Y')],
                ['task' => 'Suivi commande fournisseur', 'priority' => 'medium', 'deadline' => now()->addDays(2)->format('d M Y')],
                ['task' => 'Rapport hebdomadaire', 'priority' => 'low', 'deadline' => now()->addDays(3)->format('d M Y')],
            ]
        ];

        return view('dashboards.operational', compact('data'));
    }
}