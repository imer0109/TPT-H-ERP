<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string|max:50|unique:warehouses,code,' . ($this->warehouse ? $this->warehouse->id : ''),
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:principal,secondaire,production,logistique',
            'adresse' => 'nullable|string|max:255',
            'actif' => 'boolean',

            // Polymorphic fields
            'entity_type' => 'required|string|in:App\Models\Company,App\Models\Agency',
            'entity_id' => 'required|integer',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => 'Le code du dépôt est obligatoire',
            'code.unique' => 'Ce code de dépôt existe déjà',
            'nom.required' => 'Le nom du dépôt est obligatoire',
            'type.required' => 'Le type de dépôt est obligatoire',
            'entity_type.required' => 'Le type d’entité est obligatoire',
            'entity_id.required' => 'L’entité est obligatoire',
        ];
    }
}