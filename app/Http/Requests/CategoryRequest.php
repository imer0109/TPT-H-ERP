<?php

namespace App\Http\Requests;

use App\Decorators\ApiRequestDecorator;
use App\Models\Category;
use App\Models\TypeProduct;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends ApiRequestDecorator
{
    /**
     * @inheritDoc
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:' . Category::class . ',name'],
            'type_product_id' => ['required', 'exists:' . TypeProduct::class . ',id'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function attributes(): array
    {
        return [
            'name' => "Le nom",
            'type_product_id' => "Le Type de produit",
        ];
    }
}
