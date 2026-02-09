<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\HrDashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\HR\HrReportController;
use App\Http\Controllers\DocumentController;

Route::middleware(['auth'])->prefix('hr')->name('hr.')->group(function () {
    // Dashboard
    Route::get('/', [HrDashboardController::class, 'index'])->name('dashboard');

    // Employés
    Route::resource('employees', EmployeeController::class);

    // Congés
    Route::resource('leaves', LeaveController::class);
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');

    // Pointages
    Route::resource('attendances', AttendanceController::class);
    Route::get('attendances/today', [AttendanceController::class, 'today'])->name('attendances.today');
    Route::post('attendances/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.check-in');
    Route::post('attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.check-out');

    // Paie
    Route::resource('payslips', PayslipController::class);
    Route::get('payslips/{payslip}/pdf', [PayslipController::class, 'generatePdf'])->name('payslips.pdf');

    // Évaluations
    Route::resource('evaluations', EvaluationController::class);

    // Rapports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [HrReportController::class, 'dashboard'])->name('index');
        Route::get('/attendance', [HrReportController::class, 'attendance'])->name('attendance');
        Route::get('/leaves', [HrReportController::class, 'leave'])->name('leaves');
        Route::get('/payroll', [HrReportController::class, 'payroll'])->name('payroll');
        Route::get('/headcount', [HrReportController::class, 'headcount'])->name('headcount');
        Route::get('/turnover', [HrReportController::class, 'turnover'])->name('turnover');
    });

    // Documents
    Route::resource('documents', DocumentController::class);
});
