<?php

namespace App\Http\Requests;

use App\Decorators\ApiRequestDecorator;
use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends ApiRequestDecorator
{
    /**
     * @inheritDoc
     */
    public static function rules(): array
    {
        return [
            "title" => ['required', 'string'],
            'type' => ['required', 'string'],
            "author" => ['required', 'string'],
            "description" => ['required', 'string'],
            "content" => ['required', 'string'],
            'files' => ['required', 'array', 'max:11'],
            'files.*' => ['image', 'max:5120', 'mimes:jpeg,png,jpg'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function attributes(): array
    {
        return [
            'type' => "Le domaine",
            'title' => "Le titre",
            'description' => "La description",
            'author' => "L'auteur de la publication",
            'content' => "Le contenu",
            'files' => "Le fichier image",
            'files.*' => "Le fichier image",
        ];
    }
}
