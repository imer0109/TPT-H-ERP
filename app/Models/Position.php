<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'parent_position_id',
        'department_id',
        'is_management'
    ];

    protected $casts = [
        'is_management' => 'boolean',
    ];

    // Relations
    public function parentPosition()
    {
        return $this->belongsTo(Position::class, 'parent_position_id');
    }

    public function childPositions()
    {
        return $this->hasMany(Position::class, 'parent_position_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'current_position_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Accessors
    public function getFullHierarchyAttribute()
    {
        $hierarchy = [];
        $position = $this;
        
        while ($position) {
            array_unshift($hierarchy, $position->title);
            $position = $position->parentPosition;
        }
        
        return implode(' > ', $hierarchy);
    }
}