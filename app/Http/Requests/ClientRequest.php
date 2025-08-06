<?php

namespace App\Http\Requests;

use App\Decorators\ApiRequestDecorator;
use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends ApiRequestDecorator
{
    /**
     * @inheritDoc
     */
    public static function rules(): array
    {
        return [
            "name" => ['required', 'string'],
            "comment" => ['required', 'string'],
            "site" => ['nullable', 'string'],
            'file' => ['required', 'image', 'max:5120', 'mimes:jpeg,png,jpg'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function attributes(): array
    {
        return [
            'name' => "Le nom du client",
            'comment' => "Le commentaire",
            'site' => "Le site du client",
            'file' => "Le fichier image",
        ];
    }
}
