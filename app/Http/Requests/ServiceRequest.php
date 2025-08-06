<?php

namespace App\Http\Requests;

use App\Decorators\ApiRequestDecorator;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends ApiRequestDecorator
{
    /**
     * @inheritDoc
     */
    public static function rules(): array
    {
        return [
            "title" => ['required', 'string'],
            "description" => ['required', 'string'],
            "content" => ['required', 'string'],
            'file' => ['required', 'image', 'max:5120', 'mimes:jpeg,png,jpg'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function attributes(): array
    {
        return [
            'title' => "Le titre",
            'description' => "La description",
            'content' => "Le contenu",
            'file' => "Le fichier image",
        ];
    }
}
