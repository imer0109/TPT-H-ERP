<?php

namespace App\Http\Controllers;

use App\Models\ApiConnector;
use App\Models\ApiDataMapping;
use App\Services\DataTransformationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ApiDataMappingController extends Controller
{
    protected $transformationService;

    public function __construct(DataTransformationService $transformationService)
    {
        $this->middleware('auth');
        $this->transformationService = $transformationService;
    }

    /**
     * Display mappings for a connector
     */
    public function index(ApiConnector $connector, Request $request)
    {
        $query = $connector->dataMappings();

        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->entity_type);
        }

        $mappings = $query->paginate(20);
        $entityTypes = ApiDataMapping::getEntityTypes();

        return view('api-connectors.mappings.index', compact('connector', 'mappings', 'entityTypes'));
    }

    /**
     * Show form for creating new mapping
     */
    public function create(ApiConnector $connector, Request $request)
    {
        $entityTypes = ApiDataMapping::getEntityTypes();
        $fieldTypes = ApiDataMapping::getFieldTypes();
        $entityType = $request->get('entity_type');

        return view('api-connectors.mappings.create', compact('connector', 'entityTypes', 'fieldTypes', 'entityType'));
    }

    /**
     * Store new mapping
     */
    public function store(Request $request, ApiConnector $connector)
    {
        $request->validate([
            'entity_type' => ['required', Rule::in(array_keys(ApiDataMapping::getEntityTypes()))],
            'external_field' => 'required|string|max:255',
            'internal_field' => 'required|string|max:255',
            'field_type' => ['required', Rule::in(array_keys(ApiDataMapping::getFieldTypes()))],
            'transformation_rules' => 'nullable|array',
            'validation_rules' => 'nullable|array',
            'is_required' => 'boolean',
            'default_value' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Check for duplicate mapping
        $exists = $connector->dataMappings()
            ->where('entity_type', $request->entity_type)
            ->where('external_field', $request->external_field)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Un mapping existe déjà pour ce champ externe dans cette entité.');
        }

        ApiDataMapping::create([
            'connector_id' => $connector->id,
            'entity_type' => $request->entity_type,
            'external_field' => $request->external_field,
            'internal_field' => $request->internal_field,
            'field_type' => $request->field_type,
            'transformation_rules' => $request->transformation_rules ?? [],
            'validation_rules' => $request->validation_rules ?? [],
            'is_required' => $request->boolean('is_required'),
            'default_value' => $request->default_value,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('api-connectors.mappings.index', $connector)
            ->with('success', 'Mapping créé avec succès.');
    }

    /**
     * Show mapping details
     */
    public function show(ApiConnector $connector, ApiDataMapping $mapping)
    {
        return view('api-connectors.mappings.show', compact('connector', 'mapping'));
    }

    /**
     * Show edit form
     */
    public function edit(ApiConnector $connector, ApiDataMapping $mapping)
    {
        $entityTypes = ApiDataMapping::getEntityTypes();
        $fieldTypes = ApiDataMapping::getFieldTypes();

        return view('api-connectors.mappings.edit', compact('connector', 'mapping', 'entityTypes', 'fieldTypes'));
    }

    /**
     * Update mapping
     */
    public function update(Request $request, ApiConnector $connector, ApiDataMapping $mapping)
    {
        $request->validate([
            'entity_type' => ['required', Rule::in(array_keys(ApiDataMapping::getEntityTypes()))],
            'external_field' => 'required|string|max:255',
            'internal_field' => 'required|string|max:255',
            'field_type' => ['required', Rule::in(array_keys(ApiDataMapping::getFieldTypes()))],
            'transformation_rules' => 'nullable|array',
            'validation_rules' => 'nullable|array',
            'is_required' => 'boolean',
            'default_value' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Check for duplicate mapping (excluding current one)
        $exists = $connector->dataMappings()
            ->where('entity_type', $request->entity_type)
            ->where('external_field', $request->external_field)
            ->where('id', '!=', $mapping->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Un mapping existe déjà pour ce champ externe dans cette entité.');
        }

        $mapping->update([
            'entity_type' => $request->entity_type,
            'external_field' => $request->external_field,
            'internal_field' => $request->internal_field,
            'field_type' => $request->field_type,
            'transformation_rules' => $request->transformation_rules ?? [],
            'validation_rules' => $request->validation_rules ?? [],
            'is_required' => $request->boolean('is_required'),
            'default_value' => $request->default_value,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('api-connectors.mappings.show', [$connector, $mapping])
            ->with('success', 'Mapping mis à jour avec succès.');
    }

    /**
     * Delete mapping
     */
    public function destroy(ApiConnector $connector, ApiDataMapping $mapping)
    {
        $mapping->delete();

        return redirect()->route('api-connectors.mappings.index', $connector)
            ->with('success', 'Mapping supprimé avec succès.');
    }

    /**
     * Generate mapping suggestions
     */
    public function suggestions(Request $request, ApiConnector $connector)
    {
        $request->validate([
            'sample_data' => 'required|array',
            'entity_type' => ['required', Rule::in(array_keys(ApiDataMapping::getEntityTypes()))]
        ]);

        $suggestions = $this->transformationService->suggestMappings(
            $request->sample_data,
            $request->entity_type
        );

        return response()->json($suggestions);
    }

    /**
     * Test transformation
     */
    public function testTransformation(Request $request, ApiConnector $connector, ApiDataMapping $mapping)
    {
        $request->validate([
            'test_value' => 'required'
        ]);

        try {
            $transformedValue = $mapping->transformValue($request->test_value);
            $validationErrors = $mapping->validateValue($transformedValue);

            return response()->json([
                'success' => true,
                'transformed_value' => $transformedValue,
                'validation_errors' => $validationErrors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Bulk create mappings
     */
    public function bulkCreate(Request $request, ApiConnector $connector)
    {
        $request->validate([
            'mappings' => 'required|array',
            'mappings.*.entity_type' => ['required', Rule::in(array_keys(ApiDataMapping::getEntityTypes()))],
            'mappings.*.external_field' => 'required|string|max:255',
            'mappings.*.internal_field' => 'required|string|max:255',
            'mappings.*.field_type' => ['required', Rule::in(array_keys(ApiDataMapping::getFieldTypes()))],
            'mappings.*.is_required' => 'boolean'
        ]);

        $created = 0;
        $skipped = 0;
        $errors = [];

        foreach ($request->mappings as $mappingData) {
            try {
                // Check if mapping already exists
                $exists = $connector->dataMappings()
                    ->where('entity_type', $mappingData['entity_type'])
                    ->where('external_field', $mappingData['external_field'])
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                ApiDataMapping::create([
                    'connector_id' => $connector->id,
                    'entity_type' => $mappingData['entity_type'],
                    'external_field' => $mappingData['external_field'],
                    'internal_field' => $mappingData['internal_field'],
                    'field_type' => $mappingData['field_type'],
                    'is_required' => $mappingData['is_required'] ?? false,
                    'is_active' => true
                ]);

                $created++;

            } catch (\Exception $e) {
                $errors[] = "Erreur pour {$mappingData['external_field']}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'created' => $created,
            'skipped' => $skipped,
            'errors' => $errors
        ]);
    }

    /**
     * Export mappings configuration
     */
    public function export(ApiConnector $connector, Request $request)
    {
        $query = $connector->dataMappings();

        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->entity_type);
        }

        $mappings = $query->get()->map(function($mapping) {
            return [
                'entity_type' => $mapping->entity_type,
                'external_field' => $mapping->external_field,
                'internal_field' => $mapping->internal_field,
                'field_type' => $mapping->field_type,
                'transformation_rules' => $mapping->transformation_rules,
                'validation_rules' => $mapping->validation_rules,
                'is_required' => $mapping->is_required,
                'default_value' => $mapping->default_value,
                'is_active' => $mapping->is_active
            ];
        });

        $filename = 'mappings_' . $connector->type . '_' . now()->format('Y-m-d_H-i-s') . '.json';

        return response()->json($mappings)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Import mappings configuration
     */
    public function import(Request $request, ApiConnector $connector)
    {
        $request->validate([
            'mappings_file' => 'required|file|mimes:json'
        ]);

        try {
            $mappingsData = json_decode(file_get_contents($request->file('mappings_file')), true);
            
            if (!is_array($mappingsData)) {
                throw new \Exception('Format de fichier invalide');
            }

            $created = 0;
            $updated = 0;
            $errors = [];

            foreach ($mappingsData as $mappingData) {
                try {
                    $existing = $connector->dataMappings()
                        ->where('entity_type', $mappingData['entity_type'])
                        ->where('external_field', $mappingData['external_field'])
                        ->first();

                    if ($existing) {
                        $existing->update($mappingData);
                        $updated++;
                    } else {
                        ApiDataMapping::create(array_merge($mappingData, [
                            'connector_id' => $connector->id
                        ]));
                        $created++;
                    }

                } catch (\Exception $e) {
                    $errors[] = "Erreur pour {$mappingData['external_field']}: " . $e->getMessage();
                }
            }

            return redirect()->route('api-connectors.mappings.index', $connector)
                ->with('success', "Importation terminée: {$created} créés, {$updated} mis à jour, " . count($errors) . " erreurs.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }
}