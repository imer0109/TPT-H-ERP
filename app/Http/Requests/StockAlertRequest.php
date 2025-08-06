<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockAlertRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ajustez selon vos besoins d'autorisation
    }

    public function rules()
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'minimum_threshold' => ['required', 'numeric', 'min:0'],
            'security_threshold' => ['required', 'numeric', 'min:0', 'gte:minimum_threshold'],
            'is_active' => ['boolean'],
            'email_notifications' => ['boolean'],
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Le produit est obligatoire.',
            'product_id.exists' => 'Le produit sélectionné n\'existe pas.',
            'warehouse_id.required' => 'L\'entrepôt est obligatoire.',
            'warehouse_id.exists' => 'L\'entrepôt sélectionné n\'existe pas.',
            'minimum_threshold.required' => 'Le seuil minimum est obligatoire.',
            'minimum_threshold.numeric' => 'Le seuil minimum doit être un nombre.',
            'minimum_threshold.min' => 'Le seuil minimum doit être supérieur ou égal à 0.',
            'security_threshold.required' => 'Le seuil de sécurité est obligatoire.',
            'security_threshold.numeric' => 'Le seuil de sécurité doit être un nombre.',
            'security_threshold.min' => 'Le seuil de sécurité doit être supérieur ou égal à 0.',
            'security_threshold.gte' => 'Le seuil de sécurité doit être supérieur ou égal au seuil minimum.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'email_notifications' => $this->boolean('email_notifications'),
        ]);
    }
}