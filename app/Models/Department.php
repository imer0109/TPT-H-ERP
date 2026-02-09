<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'head_id'
    ];

    /**
     * Get the head of this department
     */
    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    /**
     * Get all teams in this department
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get all users in this department
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}