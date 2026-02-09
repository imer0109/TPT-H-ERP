<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DashboardTestController extends Controller
{
    public function testDashboard()
    {
        try {
            // Test de la vue du dashboard
            $data = [
                'title' => 'Dashboard Test',
                'user' => Auth::user(),
                'test_message' => 'Si vous voyez ce message, le dashboard fonctionne correctement',
                'blade_works' => true
            ];
            
            Log::info('Test Dashboard - Vue atteinte');
            
            // Essayer de rendre la vue
            $rendered = view('dashboard', $data)->render();
            Log::info('Test Dashboard - Vue rendue, longueur: ' . strlen($rendered));
            
            return view('dashboard', $data);
        } catch (\Exception $e) {
            Log::error('Erreur Dashboard: ' . $e->getMessage());
            
            // Si la vue dashboard ne fonctionne pas, retourner une vue simple
            $data['error'] = $e->getMessage();
            return view('welcome', $data);
        }
    }
}
