<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'total_allocated',
        'total_used',
        'balance',
        'effective_date',
        'expiry_date',
        'notes'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date'
    ];

    // Relations
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('expiry_date', '>=', now())
            ->orWhereNull('expiry_date');
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeForLeaveType($query, $leaveTypeId)
    {
        return $query->where('leave_type_id', $leaveTypeId);
    }

    // Methods
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    public function hasAvailableBalance()
    {
        return $this->balance > 0;
    }

    public function useDays($days)
    {
        if ($this->balance >= $days) {
            $this->total_used += $days;
            $this->balance -= $days;
            $this->save();
            return true;
        }
        return false;
    }

    public function addDays($days)
    {
        $this->total_allocated += $days;
        $this->balance += $days;
        $this->save();
        return true;
    }
}