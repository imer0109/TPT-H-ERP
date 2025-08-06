<?php

namespace App\Models;

use App\Enums\LogTypeEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use function PHPSTORM_META\type;

class Log extends Model
{
    use HasFactory, HasApiTokens, HasUuids;

    protected $guarded = ['id'];

    public $timestamps = false;

    public static function create(LogTypeEnum $type, string $description){
        static::query()->create([
            'date' => now(),
            'type' => $type->value,
            'user_id' => auth()->id(),
            'description' => $description
        ]);
    }

    protected function casts(){
        return[
            'type' => LogTypeEnum::class
        ];
    }


    public function user(){
        return $this->belongsTo(User::class);
    }
}
