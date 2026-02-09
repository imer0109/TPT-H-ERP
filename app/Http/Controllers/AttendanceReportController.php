<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceReportController extends Controller
{
    /**
     * Show daily attendance report.
     */
    public function daily(Request $request)
    {
        $date = $request->get('date', today()->toDateString());
        
        $attendances = Attendance::with('employee')
            ->whereDate('date', $date)
            ->get();

        return view('attendances.reports.daily', compact('attendances', 'date'));
    }

    /**
     * Show monthly attendance report.
     */
    public function monthly(Request $request)
    {
        $month = $request->get('month', today()->month);
        $year = $request->get('year', today()->year);
        
        $attendances = Attendance::with('employee')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        return view('attendances.reports.monthly', compact('attendances', 'month', 'year'));
    }
}