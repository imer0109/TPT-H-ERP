<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockMovementRequest extends FormRequest
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
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:entree,sortie',
            'source' => 'required|in:achat,production,don,vente,consommation,perte,transfert',
            'quantite' => 'required|numeric|min:0.01',
            'unite' => 'required|string|max:20',
            'prix_unitaire' => 'required|numeric|min:0',
            'motif' => 'required|string|max:255',
            'reference' => 'nullable|string|max:100',
            'montant_total' => 'nullable|numeric|min:0',
            'source_entity_type' => 'nullable|string|max:100',
            'source_entity_id' => 'nullable|integer',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string'
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
            'warehouse_id.required' => 'Le dépôt est obligatoire',
            'warehouse_id.exists' => 'Le dépôt sélectionné n\'existe pas',
            'product_id.required' => 'Le produit est obligatoire',
            'product_id.exists' => 'Le produit sélectionné n\'existe pas',
            'type.required' => 'Le type de mouvement est obligatoire',
            'type.in' => 'Le type de mouvement doit être une entrée ou une sortie',
            'source.required' => 'La source du mouvement est obligatoire',
            'source.in' => 'La source du mouvement doit être valide',
            'quantite.required' => 'La quantité est obligatoire',
            'quantite.numeric' => 'La quantité doit être un nombre',
            'quantite.min' => 'La quantité doit être supérieure à 0',
            'unite.required' => 'L\'unité est obligatoire',
            'prix_unitaire.required' => 'Le prix unitaire est obligatoire',
            'prix_unitaire.numeric' => 'Le prix unitaire doit être un nombre',
            'prix_unitaire.min' => 'Le prix unitaire doit être supérieur ou égal à 0',
            'motif.required' => 'Le motif est obligatoire',
            'montant_total.numeric' => 'Le montant total doit être un nombre',
            'montant_total.min' => 'Le montant total doit être supérieur ou égal à 0',
            'justificatif.file' => 'Le justificatif doit être un fichier',
            'justificatif.mimes' => 'Le justificatif doit être un fichier PDF, JPG, JPEG ou PNG',
            'justificatif.max' => 'Le justificatif ne doit pas dépasser 2Mo'
        ];
    }
}