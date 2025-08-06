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
            "name" => ['required', 'string', 'unique:products,name'],
            'category_id' => ['required', 'exists:categories,id'],
            'created_at' => ['required', 'string'],
            'site' => ['nullable', 'string'],
            'description' => ['required', 'string'],
            'functionality' => ['required', 'string'],
            'files' => ['required', 'array', 'max:5'],
            'files.*' => ['image', 'max:5120', 'mimes:jpeg,png,jpg'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function attributes(): array
    {
        return [
            'name' => "Le nom du produit",
            'category_id' => "La catégorie du produit",
            'created_at' => "L'année de création du produit",
            'site' => "Le site du produit",
            'description' => "La description du produit",
            'functionality' => "La fonctionnalité du produit",
            'files' => "Les fichiers images",
            'files.*' => "Le fichier image",
        ];
    }
}
