<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductWebRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id ?? null;
        return [
            'name' => ['required', 'string', 'max:255', 'unique:products,name,'.($productId ?: 'NULL').',id'],
            'description' => ['nullable', 'string'],
            'quantite' => ['required', 'integer', 'min:0'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
        ];
    }
}


