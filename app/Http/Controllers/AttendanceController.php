<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendances = Attendance::with('employee')
            ->when($request->date, function($query, $date) {
                $query->whereDate('date', $date);
            })
            ->when($request->employee_id, function($query, $employee_id) {
                $query->where('employee_id', $employee_id);
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->paginate(15);

        return view('attendances.index', compact('attendances'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'photo' => 'required|image|max:2048'
        ]);

        $now = now();
        $photo = $request->file('photo')->store('attendances/photos', 'public');

        // Vérifier si l'employé n'a pas déjà pointé aujourd'hui
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $now->toDateString())
            ->first();

        if ($existingAttendance) {
            Storage::disk('public')->delete($photo);
            return back()->with('error', 'Vous avez déjà pointé aujourd\'hui');
        }

        // Calculer les minutes de retard
        $employee = Employee::find($request->employee_id);
        $scheduleStart = Carbon::createFromTimeString($employee->schedule_start);
        $lateMinutes = $now->diffInMinutes($scheduleStart, false);

        Attendance::create([
            'employee_id' => $request->employee_id,
            'date' => $now->toDateString(),
            'check_in' => $now->toTimeString(),
            'check_in_photo' => $photo,
            'late_minutes' => max(0, $lateMinutes),
            'status' => 'present'
        ]);

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Pointage d\'entrée enregistré');
    }

    public function checkOut(Request $request, Attendance $attendance)
    {
        $request->validate([
            'photo' => 'required|image|max:2048'
        ]);

        if ($attendance->check_out) {
            return back()->with('error', 'Le pointage de sortie a déjà été enregistré');
        }

        $now = now();
        $photo = $request->file('photo')->store('attendances/photos', 'public');

        // Calculer les minutes supplémentaires
        $employee = $attendance->employee;
        $scheduleEnd = Carbon::createFromTimeString($employee->schedule_end);
        $overtimeMinutes = $now->diffInMinutes($scheduleEnd, false);

        $attendance->update([
            'check_out' => $now->toTimeString(),
            'check_out_photo' => $photo,
            'overtime_minutes' => max(0, $overtimeMinutes)
        ]);

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Pointage de sortie enregistré');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('employee');
        return view('attendances.show', compact('attendance'));
    }

    public function report(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'employee_id' => 'nullable|exists:employees,id'
        ]);

        $attendances = Attendance::with('employee')
            ->whereBetween('date', [
                $request->start_date,
                $request->end_date
            ])
            ->when($request->employee_id, function($query, $employee_id) {
                $query->where('employee_id', $employee_id);
            })
            ->orderBy('date')
            ->get()
            ->groupBy('employee_id');

        return view('attendances.report', compact('attendances'));
    }
}