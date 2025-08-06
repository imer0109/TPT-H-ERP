<?php

namespace App\Models;

use App\Traits\Routing\GenerateUniqueSlugTrait;
use App\Traits\Routing\ModelsSlugKeyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Blog extends Model
{
    /** @use HasFactory<\Database\Factories\BlogFactory> */
    use HasFactory, HasApiTokens, HasUuids;

    protected $guarded = ['id'];

    // public $timestamps = false;

    public function comments()
    {
        return $this->hasMany(Comment::class, 'blog_id');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'owner');
    }
}
