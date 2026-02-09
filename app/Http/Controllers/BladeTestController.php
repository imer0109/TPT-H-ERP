<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BladeTestController extends Controller
{
    public function testBlade()
    {
        try {
            // Test 1 : Vue simple
            $content1 = view('test-blade')->render();
            
            Log::info('Test Blade 1 - Longueur: ' . strlen($content1));
            
            // Test 2 : Vue avec variables
            $content2 = view('test-blade', ['test' => 'Valeur de test'])->render();
            Log::info('Test Blade 2 - Contient variable: ' . (strpos($content2, 'Valeur de test') !== false ? 'OUI' : 'NON'));
            
            // Test 3 : Layout
            $content3 = view('layouts.app')->render();
            Log::info('Test Blade 3 - Layout - Longueur: ' . strlen($content3));
            
            return response()->json([
                'success' => true,
                'tests' => [
                    'simple_view' => strlen($content1) > 0,
                    'variables' => strpos($content2, 'Valeur de test') !== false,
                    'layout' => strlen($content3) > 0
                ],
                'content_lengths' => [
                    'simple' => strlen($content1),
                    'with_vars' => strlen($content2),
                    'layout' => strlen($content3)
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur Blade: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    
    public function testBladePage()
    {
        return view('test-blade', ['test' => 'Valeur passée depuis le contrôleur']);
    }
}