<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EntityController extends Controller
{
    public function getEntitiesByType(Request $request)
    {
        $type = $request->query('type');
        
        // Journaliser la requête pour le débogage
        Log::info('getEntitiesByType appelé avec type: ' . $type);
        
        if ($type === 'App\Models\Company') {
            try {
                $entities = Company::select('id', 'raison_sociale')->get();
                Log::info('Nombre de companies trouvées: ' . $entities->count());
                $entities = $entities->map(function($entity) use ($type) {
                    return [
                        'id' => $entity->id,
                        'raison_sociale' => $entity->raison_sociale,
                        'nom' => $entity->raison_sociale, // Pour compatibilité
                        'type' => $type
                    ];
                });
            } catch (\Exception $e) {
                Log::error('Erreur lors de la récupération des companies: ' . $e->getMessage());
                return response()->json(['error' => 'Erreur lors de la récupération des companies'], 500);
            }
        } elseif ($type === 'App\Models\Agency') {
            try {
                $entities = Agency::select('id', 'nom')->get();
                Log::info('Nombre d\'agences trouvées: ' . $entities->count());
                $entities = $entities->map(function($entity) use ($type) {
                    return [
                        'id' => $entity->id,
                        'nom' => $entity->nom,
                        'type' => $type
                    ];
                });
            } catch (\Exception $e) {
                Log::error('Erreur lors de la récupération des agences: ' . $e->getMessage());
                return response()->json(['error' => 'Erreur lors de la récupération des agences'], 500);
            }
        } else {
            Log::warning('Type d\'entité non reconnu: ' . $type);
            return response()->json(['error' => 'Type d\'entité non reconnu'], 400);
        }
        
        Log::info('Retour de ' . count($entities) . ' entités');
        return response()->json($entities);
    }
}