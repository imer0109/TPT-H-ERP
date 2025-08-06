<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'photo',
        'statut'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function societes()
    {
        return $this->morphedByMany(Company::class, 'entity', 'user_entity')
            ->withPivot(['date_debut', 'date_fin']);
    }

    public function agences()
    {
        return $this->morphedByMany(Agency::class, 'entity', 'user_entity')
            ->withPivot(['date_debut', 'date_fin']);
    }

    public function hasRole($role)
    {
        return $this->roles()->where('nom', $role)->exists();
    }

    public function hasPermission($permission)
    {
        return $this->roles()->whereHas('permissions', function($query) use ($permission) {
            $query->where('nom', $permission);
        })->exists();
    }
    
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->nom . ' ' . $this->prenom;
    }
}
