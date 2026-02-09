<?php

namespace App\Http\Controllers\HR;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Attendance;
use App\Models\Payslip;
use App\Models\Position;
use App\Models\Company;
use App\Models\Agency;
use App\Models\LeaveType;
use App\Models\Evaluation;
use App\Models\Contract;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class HrReportController extends Controller
{
    /**
     * Show HR dashboard.
     */
    public function dashboard(Request $request)
    {
        // Check if user has permission to view HR reports
        if (!Auth::user()->hasPermission('hr.reports.view') && !Auth::user()->hasRole('drh')) {
            abort(403, 'Unauthorized access to HR reports');
        }
        
        // Headcount data
        $employees = Employee::all();
        $headcountData = [
            'total' => $employees->count(),
            'active' => $employees->where('status', 'active')->count(),
            'suspended' => $employees->where('status', 'suspended')->count(),
            'archived' => $employees->where('status', 'archived')->count(),
        ];
        
        // Department stats
        $departmentStats = [];
        foreach ($employees as $employee) {
            $department = $employee->currentPosition->department->nom ?? 'Non assigné';
            if (!isset($departmentStats[$department])) {
                $departmentStats[$department] = 0;
            }
            $departmentStats[$department]++;
        }
        
        // Payroll data (current month)
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $payslips = Payslip::whereBetween('period_start', [$startDate, $endDate])->get();
        $payrollData = [
            'total_net' => $payslips->sum('net_salary'),
            'total_employees' => $payslips->count(),
        ];
        
        // Leave data (current month)
        $leaves = Leave::where('status', 'approved')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->get();
        $leaveData = [
            'approved' => $leaves->count(),
        ];
        
        // Attendance data (current month)
        $attendances = Attendance::whereBetween('date', [$startDate, $endDate])->get();
        $totalDays = $attendances->count();
        $presentDays = $attendances->where('status', 'present')->count();
        $attendanceData = [
            'attendance_rate' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0,
        ];
        
        // Turnover data (current year)
        $year = Carbon::now()->year;
        $startDateYear = Carbon::create($year, 1, 1);
        $endDateYear = Carbon::create($year, 12, 31);
        $hires = Employee::whereBetween('date_embauche', [$startDateYear, $endDateYear])->count();
        $departures = Employee::whereHas('contracts', function($query) use ($startDateYear, $endDateYear) {
            $query->whereBetween('end_date', [$startDateYear, $endDateYear]);
        })->count();
        $startCount = Employee::where('date_embauche', '<=', $startDateYear)
            ->where(function($query) use ($startDateYear) {
                $query->whereNull('deleted_at')
                      ->orWhere('deleted_at', '>', $startDateYear);
            })->count();
        $endCount = Employee::where('date_embauche', '<=', $endDateYear)
            ->where(function($query) use ($endDateYear) {
                $query->whereNull('deleted_at')
                      ->orWhere('deleted_at', '>', $endDateYear);
            })->count();
        $averageHeadcount = ($startCount + $endCount) / 2;
        $turnoverRate = $averageHeadcount > 0 ? round((($departures / $averageHeadcount) * 100), 2) : 0;
        $turnoverData = [
            'hires' => $hires,
            'departures' => $departures,
            'turnover_rate' => $turnoverRate,
        ];
        
        // Recent hires (last 5)
        $recentHires = Employee::with('currentPosition')
            ->orderBy('date_embauche', 'desc')
            ->limit(5)
            ->get();
            
        // Recent leaves (last 5)
        $recentLeaves = Leave::with('employee', 'leaveType')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Recent evaluations (last 5)
        $recentEvaluations = Evaluation::with('employee', 'evaluator')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Alerts
        $alerts = [
            'contracts_expiring' => Contract::whereBetween('end_date', [Carbon::now(), Carbon::now()->addDays(30)])
                ->where('status', 'active')
                ->count(),
            'probation_ending' => Contract::whereBetween('trial_period_end', [Carbon::now(), Carbon::now()->addDays(30)])
                ->where('status', 'active')
                ->count(),
            'long_leaves' => Leave::where('status', 'approved')
                ->where('end_date', '>', Carbon::now()->addDays(15))
                ->count(),
            'payroll_ready' => Payslip::where('status', 'validated')
                ->whereBetween('period_start', [$startDate, $endDate])
                ->count(),
        ];
        
        return view('hr.reports.dashboard', compact(
            'headcountData',
            'departmentStats',
            'payrollData',
            'leaveData',
            'attendanceData',
            'turnoverData',
            'recentHires',
            'recentLeaves',
            'recentEvaluations',
            'alerts'
        ));
    }

    /**
     * Show headcount report.
     */
    public function headcount(Request $request)
    {
        // Check if user has permission to view HR reports
        if (!Auth::user()->hasPermission('hr.reports.view') && !Auth::user()->hasRole('drh')) {
            abort(403, 'Unauthorized access to HR reports');
        }
        
        $employees = Employee::with('currentPosition', 'currentCompany', 'currentAgency')->get();
        
        return view('hr.reports.headcount', compact('employees'));
    }

    /**
     * Show turnover report.
     */
    public function turnover(Request $request)
    {
        // Check if user has permission to view HR reports
        if (!Auth::user()->hasPermission('hr.reports.view') && !Auth::user()->hasRole('drh')) {
            abort(403, 'Unauthorized access to HR reports');
        }
        
        $year = $request->get('year', today()->year);
        
        // Get turnover data for the year
        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create($year, 12, 31);
        
        // Get hires for the year
        $hires = Employee::whereBetween('date_embauche', [$startDate, $endDate])->count();
        
        // Get departures for the year (employees with end dates in this period)
        $departures = Employee::whereHas('contracts', function($query) use ($startDate, $endDate) {
            $query->whereBetween('end_date', [$startDate, $endDate]);
        })->count();
        
        // Calculate average headcount
        $startCount = Employee::where('date_embauche', '<=', $startDate)
            ->where(function($query) use ($startDate) {
                $query->whereNull('deleted_at')
                      ->orWhere('deleted_at', '>', $startDate);
            })->count();
            
        $endCount = Employee::where('date_embauche', '<=', $endDate)
            ->where(function($query) use ($endDate) {
                $query->whereNull('deleted_at')
                      ->orWhere('deleted_at', '>', $endDate);
            })->count();
            
        $averageHeadcount = ($startCount + $endCount) / 2;
        
        // Calculate turnover rate
        $turnoverRate = $averageHeadcount > 0 ? round((($departures / $averageHeadcount) * 100), 2) : 0;
        
        $turnoverData = [
            'hires' => $hires,
            'departures' => $departures,
            'average_headcount' => $averageHeadcount,
            'turnover_rate' => $turnoverRate
        ];
        
        return view('hr.reports.turnover', compact('turnoverData', 'year'));
    }

    /**
     * Show payroll report.
     */
    public function payroll(Request $request)
    {
        // Check if user has permission to view HR reports
        if (!Auth::user()->hasPermission('hr.reports.view') && !Auth::user()->hasRole('drh')) {
            abort(403, 'Unauthorized access to HR reports');
        }
        
        $month = $request->get('month', today()->month);
        $year = $request->get('year', today()->year);
        
        // Get payroll data for the period
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        // Get payslips for the period
        $payslips = Payslip::whereBetween('period_start', [$startDate, $endDate])
            ->with('employee.currentPosition.department')
            ->get();
            
        // Calculate totals
        $totalEmployees = $payslips->count();
        $totalGross = $payslips->sum('gross_salary');
        $totalNet = $payslips->sum('net_salary');
        
        // Group by department
        $departmentStats = [];
        foreach ($payslips as $payslip) {
            $department = $payslip->employee->currentPosition->department->nom ?? 'Non assigné';
            if (!isset($departmentStats[$department])) {
                $departmentStats[$department] = [
                    'count' => 0,
                    'gross' => 0,
                    'net' => 0
                ];
            }
            $departmentStats[$department]['count']++;
            $departmentStats[$department]['gross'] += $payslip->gross_salary;
            $departmentStats[$department]['net'] += $payslip->net_salary;
        }
        
        $payrollData = [
            'total_employees' => $totalEmployees,
            'total_gross' => $totalGross,
            'total_net' => $totalNet,
            'department_stats' => $departmentStats
        ];
        
        return view('hr.reports.payroll', compact('payrollData', 'month', 'year'));
    }

    /**
     * Show leave report.
     */
    public function leave(Request $request)
    {
        // Check if user has permission to view HR reports
        if (!Auth::user()->hasPermission('hr.reports.view') && !Auth::user()->hasRole('drh')) {
            abort(403, 'Unauthorized access to HR reports');
        }
        
        $year = $request->get('year', today()->year);
        $leaveTypeId = $request->get('leave_type');
        
        // Get leave data for the year
        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create($year, 12, 31);
        
        $leavesQuery = Leave::whereBetween('start_date', [$startDate, $endDate])
            ->with('employee', 'leaveType');
            
        if ($leaveTypeId) {
            $leavesQuery->where('leave_type_id', $leaveTypeId);
        }
        
        $leaves = $leavesQuery->get();
        
        // Calculate statistics
        $totalLeaves = $leaves->count();
        $approvedLeaves = $leaves->where('status', 'approved')->count();
        $pendingLeaves = $leaves->where('status', 'pending')->count();
        $rejectedLeaves = $leaves->where('status', 'rejected')->count();
        
        // Group by leave type
        $leaveTypeStats = [];
        foreach ($leaves as $leave) {
            $typeName = $leave->leaveType->name ?? 'Inconnu';
            if (!isset($leaveTypeStats[$typeName])) {
                $leaveTypeStats[$typeName] = [
                    'count' => 0,
                    'approved' => 0,
                    'total_days' => 0
                ];
            }
            $leaveTypeStats[$typeName]['count']++;
            if ($leave->status === 'approved') {
                $leaveTypeStats[$typeName]['approved']++;
                $leaveTypeStats[$typeName]['total_days'] += $leave->start_date->diffInDays($leave->end_date) + 1;
            }
        }
        
        // Calculate approval rates
        foreach ($leaveTypeStats as &$stats) {
            $stats['approval_rate'] = $stats['count'] > 0 ? round(($stats['approved'] / $stats['count']) * 100, 1) : 0;
        }
        
        $leaveData = [
            'total' => $totalLeaves,
            'approved' => $approvedLeaves,
            'pending' => $pendingLeaves,
            'rejected' => $rejectedLeaves,
            'by_type' => $leaveTypeStats
        ];
        
        return view('hr.reports.leave', compact('leaveData', 'year'));
    }
    
    /**
     * Show attendance report.
     */
    public function attendance(Request $request)
    {
        // Check if user has permission to view HR reports
        if (!Auth::user()->hasPermission('hr.reports.view') && !Auth::user()->hasRole('drh')) {
            abort(403, 'Unauthorized access to HR reports');
        }
        
        $month = $request->get('month', today()->month);
        $year = $request->get('year', today()->year);
        
        // Get attendance data for the period
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        $attendances = Attendance::whereBetween('date', [$startDate, $endDate])
            ->with('employee')
            ->get();
            
        // Calculate statistics
        $totalDays = $attendances->count();
        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $lateDays = $attendances->where('status', 'late')->count();
        
        // Calculate late minutes and overtime
        $totalLateMinutes = $attendances->sum('late_minutes');
        $totalOvertimeMinutes = $attendances->sum('overtime_minutes');
        
        $attendanceData = [
            'total_days' => $totalDays,
            'present' => $presentDays,
            'absent' => $absentDays,
            'late' => $lateDays,
            'late_minutes' => $totalLateMinutes,
            'overtime_minutes' => $totalOvertimeMinutes,
            'attendance_rate' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0
        ];
        
        return view('hr.reports.attendance', compact('attendanceData', 'month', 'year'));
    }
}