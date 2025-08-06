<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Newsletter extends Model
{
    use HasFactory, HasApiTokens, HasUuids;

    protected $guarded = ['id'];

    public $timestamps = false;
}
