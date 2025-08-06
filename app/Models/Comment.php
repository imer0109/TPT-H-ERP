<?php

namespace App\Models;

use App\Models\Blog;
use App\Traits\Routing\GenerateUniqueSlugTrait;
use App\Traits\Routing\ModelsSlugKeyTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Comment extends Model
{
    use HasFactory, HasApiTokens, HasUuids;

    protected $guarded = ['id'];

    public $timestamps = false;

    // protected $casts = [
    //     'replies' => 'array',
    // ];

    public function blog() {
        return $this->belongsTo(Blog::class);
    }
    

}
