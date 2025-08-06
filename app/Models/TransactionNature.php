<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionNature extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'actif',
        'created_by'
    ];

    protected $casts = [
        'actif' => 'boolean'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
