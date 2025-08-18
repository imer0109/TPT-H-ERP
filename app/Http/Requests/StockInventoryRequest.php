<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockInventoryRequest extends FormRequest
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
        $rules = [
            'warehouse_id' => 'required|exists:warehouses,id',
            'date_inventaire' => 'required|date',
            'notes' => 'nullable|string'
        ];

        // Si c'est une mise à jour d'inventaire, valider les quantités réelles
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['items'] = 'required|array';
            $rules['items.*.id'] = 'required|exists:stock_inventory_items,id';
            $rules['items.*.quantite_reelle'] = 'required|numeric|min:0';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'warehouse_id.required' => 'Le dépôt est obligatoire',
            'warehouse_id.exists' => 'Le dépôt sélectionné n\'existe pas',
            'date_inventaire.required' => 'La date d\'inventaire est obligatoire',
            'date_inventaire.date' => 'La date d\'inventaire doit être une date valide',
            'items.required' => 'Les articles d\'inventaire sont obligatoires',
            'items.array' => 'Les articles d\'inventaire doivent être un tableau',
            'items.*.id.required' => 'L\'identifiant de l\'article est obligatoire',
            'items.*.id.exists' => 'L\'article d\'inventaire n\'existe pas',
            'items.*.quantite_reelle.required' => 'La quantité réelle est obligatoire',
            'items.*.quantite_reelle.numeric' => 'La quantité réelle doit être un nombre',
            'items.*.quantite_reelle.min' => 'La quantité réelle doit être supérieure ou égale à 0'
        ];
    }
}