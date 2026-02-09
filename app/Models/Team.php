<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'department_id'
    ];

    /**
     * Get the leader of this team
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Get the department this team belongs to
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get all users in this team
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all members of this team (excluding the leader)
     */
    public function members()
    {
        return $this->hasMany(User::class)->where('id', '!=', $this->leader_id);
    }
}