<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Agency;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    public function getEntitiesByType(Request $request)
    {
        $type = $request->query('type');
        
        if ($type === 'App\\Models\\Company') {
            $entities = Company::select('id', 'raison_sociale')->get();
            $entities = $entities->map(function($entity) use ($type) {
                return [
                    'id' => $entity->id,
                    'raison_sociale' => $entity->raison_sociale,
                    'nom' => $entity->raison_sociale, // Pour compatibilité
                    'type' => $type
                ];
            });
        } elseif ($type === 'App\\Models\\Agency') {
            $entities = Agency::select('id', 'nom')->get();
            $entities = $entities->map(function($entity) use ($type) {
                return [
                    'id' => $entity->id,
                    'nom' => $entity->nom,
                    'type' => $type
                ];
            });
        } else {
            return response()->json([], 400);
        }
        
        return response()->json($entities);
    }
}