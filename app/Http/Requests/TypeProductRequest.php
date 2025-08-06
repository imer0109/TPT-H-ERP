<?php

namespace App\Http\Requests;

use App\Decorators\ApiRequestDecorator;
use App\Models\TypeProduct;
use Illuminate\Foundation\Http\FormRequest;

class TypeProductRequest extends ApiRequestDecorator
{
    /**
     * @inheritDoc
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:' . TypeProduct::class . ',name'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function attributes(): array
    {
        return [
            'name' => "Le nom",
        ];
    }
}
