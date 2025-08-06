<?php

namespace App\Http\Requests;

use App\Decorators\ApiRequestDecorator;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UserRequest extends ApiRequestDecorator
{
    /**
     * @inheritDoc
     */
    public static function rules(): array
    {
        return [
            "lastName" => ['required', 'string'],
            "firstName" => ['required', 'string'],
            "email" => ['required', 'email', 'unique:users,email'],
            "phone" => ['nullable', 'string'],
            "role" => ['nullable', new Enum(RoleEnum::class)],
            'file' => ['nullable', 'image', 'max:5120', 'mimes:jpeg,png,jpg'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function attributes(): array
    {
        return [
            'lastName' => "Le nom",
            'firstName' => "Le prénom",
            'email' => "L'email",
            'phone' => "Le numéro de téléphone",
            'role' => "Le statut",
            'file' => "Le fichier image",
        ];
    }
}
