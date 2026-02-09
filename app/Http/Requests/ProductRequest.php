<?php

namespace App\Http\Requests;

use App\Decorators\ApiRequestDecorator;
use App\Enums\CategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends ApiRequestDecorator
{
    /**
     * @inheritDoc
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:products,name'],
            'description' => ['nullable', 'string'],
            'quantite' => ['required', 'integer', 'min:0'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function attributes(): array
    {
        return [
            'name' => "Le nom du produit",
            'description' => "La description du produit",
            'quantite' => "La quantité du produit",
            'prix_unitaire' => "Le prix unitaire du produit",
        ];
    }
}
