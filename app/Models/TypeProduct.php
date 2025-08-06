<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TypeProduct extends Model
{
    use HasFactory, HasApiTokens, HasUuids;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function categories() {
        return $this->hasMany(Category::class, 'type_product_id');
    }
}
