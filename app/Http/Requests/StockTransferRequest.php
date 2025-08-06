<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'warehouse_source_id' => 'required|exists:warehouses,id',
            'warehouse_destination_id' => 'required|exists:warehouses,id|different:warehouse_source_id',
            'product_id' => 'required|exists:products,id',
            'quantite' => 'required|numeric|min:0.01',
            'unite' => 'required|string|max:50',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'warehouse_destination_id.different' => 'Le dépôt de destination doit être différent du dépôt source',
            'quantite.min' => 'La quantité doit être supérieure à 0',
            'justificatif.mimes' => 'Le justificatif doit être un fichier PDF ou une image (JPG, JPEG, PNG)',
            'justificatif.max' => 'Le justificatif ne doit pas dépasser 2Mo'
        ];
    }
}