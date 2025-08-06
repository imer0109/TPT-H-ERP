<?php

namespace App\Http\Requests;

use App\Decorators\ApiRequestDecorator;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends ApiRequestDecorator
{
    /**
     * @inheritDoc
     */
    public static function rules(): array
    {
        return [
            'type' => ['nullable', 'string'],
            "email" => ['required', 'string', 'email'],
            "name" => ['required', 'string'],
            "phone" => ['nullable', 'string'],
            'question' => ['required', 'string'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function attributes(): array
    {
        return [
            'type' => "Le type de question",
            'email' => "Votre email",
            'name' => "Votre nom",
            'phone' => "Votre numéro de téléphone",
            'question' => "Votre question ou demande",
        ];
    }
}
