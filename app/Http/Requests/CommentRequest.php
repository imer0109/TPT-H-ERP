<?php

namespace App\Http\Requests;

use App\Decorators\ApiRequestDecorator;
use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends ApiRequestDecorator
{
    /**
     * @inheritDoc
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            "email" => ['required', 'string', 'email'],
            "comment" => ['required', 'string'],
            "blog_id" => ['required', 'exists:'.Blog::class.',id'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function attributes(): array
    {
        return [
            'name' => "Le nom",
            'email' => "L'email",
            'comment' => "Le commentaire",
            'blog_id' => "L'id du blog",
        ];
    }
}
