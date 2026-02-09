<?php

namespace App\Http\Controllers\HR;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Attendance;
use App\Models\Payslip;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HrDashboardController extends Controller
{
    /**
     * Display the HR dashboard.
     */
    public function index()
    {
        // Employee statistics
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $employeesByGender = Employee::selectRaw('gender, count(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender');
        
        // Leave statistics
        $pendingLeaves = Leave::where('status', 'pending')->count();
        $approvedLeaves = Leave::where('status', 'approved')->count();
        
        // Attendance statistics
        $today = now()->toDateString();
        $presentToday = Attendance::whereDate('date', $today)
            ->where('status', 'present')
            ->count();
        $absentToday = Attendance::whereDate('date', $today)
            ->where('status', 'absent')
            ->count();
        $lateToday = Attendance::whereDate('date', $today)
            ->where('status', 'late')
            ->count();
            
        // Payroll statistics
        $pendingPayslips = Payslip::where('status', 'draft')->count();
        $validatedPayslips = Payslip::where('status', 'validated')->count();
        $paidPayslips = Payslip::where('status', 'paid')->count();
        
        // Position statistics
        $totalPositions = Position::count();
        $positionsByLevel = Position::selectRaw('is_management, count(*) as count')
            ->groupBy('is_management')
            ->pluck('count', 'is_management');
        
        // Recent employees
        $recentEmployees = Employee::with('currentPosition', 'currentCompany')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Recent leaves
        $recentLeaves = Leave::with('employee', 'leaveType')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Recent attendance
        $recentAttendance = Attendance::with('employee')
            ->whereDate('date', $today)
            ->orderBy('check_in', 'asc')
            ->limit(5)
            ->get();

        return view('hr.dashboard.index', compact(
            'totalEmployees',
            'activeEmployees',
            'employeesByGender',
            'pendingLeaves',
            'approvedLeaves',
            'presentToday',
            'absentToday',
            'lateToday',
            'pendingPayslips',
            'validatedPayslips',
            'paidPayslips',
            'totalPositions',
            'positionsByLevel',
            'recentEmployees',
            'recentLeaves',
            'recentAttendance'
        ));
    }
}
