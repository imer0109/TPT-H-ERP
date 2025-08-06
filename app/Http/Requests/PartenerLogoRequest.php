<?php

namespace App\Http\Requests;

use App\Decorators\ApiRequestDecorator;
use Illuminate\Foundation\Http\FormRequest;

class PartenerLogoRequest extends ApiRequestDecorator
{
    /**
     * @inheritDoc
     */
    public static function rules(): array
    {
        return [
            "name" => ['required', 'string'],
            'file' => ['required', 'image', 'max:5120', 'mimes:jpeg,png,jpg'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function attributes(): array
    {
        return [
            'name' => "Le nom du partenaire",
            'file' => "Le fichier image",
        ];
    }
}
