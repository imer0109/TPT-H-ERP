<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\CashSessionController;
use App\Http\Controllers\CashTransactionController;
use App\Http\Controllers\TransactionNatureController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientReclamationController;
use App\Http\Controllers\ClientInteractionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// Routes d'authentification
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Routes de réinitialisation de mot de passe
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    // Routes pour les sociétés
    Route::resource('companies', CompanyController::class);
    
    // Routes pour les agences
    Route::resource('agencies', AgencyController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    
    // Routes pour le module de caisse
    Route::prefix('cash')->name('cash.')->group(function () {
        // Routes pour les caisses
Route::resource('registers', CashRegisterController::class)->parameters([
    'registers' => 'cashRegister'
]);
       
        // Routes pour les sessions de caisse
        Route::post('registers/{cashRegister}/open', [CashSessionController::class, 'open'])->name('sessions.open');
        Route::post('registers/{cashRegister}/sessions/{session}/close', [CashSessionController::class, 'close'])->name('sessions.close');
        Route::get('sessions/{session}/report', [CashSessionController::class, 'report'])->name('sessions.report');
        
        // Routes pour les transactions
        Route::get('registers/{cashRegister}/transactions/create', [CashTransactionController::class, 'create'])->name('transactions.create');
        Route::resource('transactions', CashTransactionController::class)->except(['create']);
        
        // Routes pour les natures de transaction
        Route::resource('natures', TransactionNatureController::class);
    });
});

// Route::get('/', function () {
//     return view('dashboard');
// })->middleware('auth');

use App\Models\CashSession; // Remplace par le bon modèle si nécessaire

Route::get('/', function () {
    $session = CashSession::latest()->first(); // Ou ta propre logique ici
    return view('dashboard', compact('session'));
})->middleware('auth');


Route::resource('permissions', PermissionController::class)->middleware('auth');
// Route::resource('users', UserController::class)->middleware('auth');

// Routes pour la gestion des stocks
Route::prefix('stock')->name('stock.')->middleware(['auth'])->group(function () {
    // Dépôts
    Route::resource('warehouses', WarehouseController::class);
    
    // Mouvements de stock
    Route::resource('movements', StockMovementController::class);
    Route::post('movements/import', [StockMovementController::class, 'import'])->name('movements.import');
    Route::get('movements/export', [StockMovementController::class, 'export'])->name('movements.export');
    
    // Transferts
    Route::resource('transfers', StockTransferController::class);
    Route::post('transfers/{transfer}/validate', [StockTransferController::class, 'validate'])->name('transfers.validate');
    Route::post('transfers/{transfer}/receive', [StockTransferController::class, 'receive'])->name('transfers.receive');
    
    // Alertes
    Route::resource('alerts', StockAlertController::class);
    Route::post('alerts/{alert}/toggle-status', [StockAlertController::class, 'toggleStatus'])->name('alerts.toggle-status');
    Route::post('alerts/{alert}/toggle-notifications', [StockAlertController::class, 'toggleNotifications'])->name('alerts.toggle-notifications');
    
    // Inventaires
    Route::resource('inventories', InventoryController::class);
    Route::post('inventories/{inventory}/validate', [InventoryController::class, 'validate'])->name('inventories.validate');
    Route::get('inventories/{inventory}/pdf', [InventoryController::class, 'generatePdf'])->name('inventories.pdf');
    
    // Rapports
    Route::get('reports/current-stock', [StockReportController::class, 'currentStock'])->name('reports.current-stock');
    Route::get('reports/movements-history', [StockReportController::class, 'movementsHistory'])->name('reports.movements-history');
    Route::get('reports/valuation', [StockReportController::class, 'valuation'])->name('reports.valuation');
    Route::get('reports/losses', [StockReportController::class, 'losses'])->name('reports.losses');
});

// Routes pour la gestion des clients
Route::middleware(['auth'])->group(function () {
    // Dashboard clients
    Route::get('clients/dashboard', [ClientController::class, 'dashboard'])->name('clients.dashboard');
    
    // Export clients
    Route::get('clients/export', [ClientController::class, 'export'])->name('clients.export');
    
    // Routes pour les clients
    Route::resource('clients', ClientController::class);
    
    // Routes pour les documents
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('documents/{document}/show', [DocumentController::class, 'show'])->name('documents.show');
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::put('documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::post('documents/download-multiple', [DocumentController::class, 'downloadMultiple'])->name('documents.download-multiple');
    
    // Routes pour les réclamations clients
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::resource('reclamations', ClientReclamationController::class);
        Route::post('reclamations/{reclamation}/change-status', [ClientReclamationController::class, 'changeStatus'])->name('reclamations.change-status');
        Route::post('reclamations/{reclamation}/assign-agent', [ClientReclamationController::class, 'assignAgent'])->name('reclamations.assign-agent');
    });
    
    // Routes pour les interactions clients
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::resource('interactions', ClientInteractionController::class);
        Route::post('interactions/{interaction}/mark-as-followed-up', [ClientInteractionController::class, 'markAsFollowedUp'])->name('interactions.mark-as-followed-up');
        Route::get('interactions/client/{client}', [ClientInteractionController::class, 'clientInteractions'])->name('interactions.client');
        Route::get('interactions/follow-ups', [ClientInteractionController::class, 'followUps'])->name('interactions.follow-ups');
    });
});

// Routes pour la gestion du personnel
Route::prefix('hr')->name('hr.')->middleware(['auth', 'verified'])->group(function () {
    // Employés
    Route::resource('employees', EmployeeController::class);
    Route::get('employees/{employee}/contract/create', [EmployeeContractController::class, 'create'])
        ->name('employees.contracts.create');
    Route::post('employees/{employee}/contract', [EmployeeContractController::class, 'store'])
        ->name('employees.contracts.store'); 
    Route::get('employees/{employee}/evaluation/create', [EmployeeEvaluationController::class, 'create'])
        ->name('employees.evaluations.create');
    Route::post('employees/{employee}/evaluation', [EmployeeEvaluationController::class, 'store'])
        ->name('employees.evaluations.store');

    // Congés
    Route::resource('leaves', LeaveController::class);
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::post('leaves/{leave}/cancel', [LeaveController::class, 'cancel'])->name('leaves.cancel');
    Route::get('leave-balance', [LeaveController::class, 'balance'])->name('leaves.balance');

    // Pointages
    Route::resource('attendances', AttendanceController::class);
    Route::post('attendances/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.check-in');
    Route::post('attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.check-out');
    Route::get('attendances/report/daily', [AttendanceReportController::class, 'daily'])->name('attendances.report.daily');
    Route::get('attendances/report/monthly', [AttendanceReportController::class, 'monthly'])->name('attendances.report.monthly');

    // Paie
    Route::resource('payroll-items', PayrollItemController::class);
    Route::resource('payslips', PayslipController::class);
    Route::post('payslips/generate', [PayslipController::class, 'generate'])->name('payslips.generate');
    Route::post('payslips/{payslip}/validate', [PayslipController::class, 'validate'])->name('payslips.validate');
    Route::post('payslips/{payslip}/pay', [PayslipController::class, 'pay'])->name('payslips.pay');
    Route::get('payslips/{payslip}/download', [PayslipController::class, 'download'])->name('payslips.download');

    // Évaluations
    Route::resource('evaluations', EvaluationController::class);
    Route::post('evaluations/{evaluation}/submit', [EvaluationController::class, 'submit'])->name('evaluations.submit');
    Route::post('evaluations/{evaluation}/acknowledge', [EvaluationController::class, 'acknowledge'])->name('evaluations.acknowledge');
    Route::post('evaluations/{evaluation}/dispute', [EvaluationController::class, 'dispute'])->name('evaluations.dispute');

    // Rapports RH
    Route::get('reports/headcount', [HrReportController::class, 'headcount'])->name('reports.headcount');
    Route::get('reports/turnover', [HrReportController::class, 'turnover'])->name('reports.turnover');
    Route::get('reports/payroll', [HrReportController::class, 'payroll'])->name('reports.payroll');
    Route::get('reports/leave', [HrReportController::class, 'leave'])->name('reports.leave');

    // Documents
    Route::get('documents/work-certificate/{employee}', [DocumentController::class, 'workCertificate'])
        ->name('documents.work-certificate');
    Route::get('documents/salary-certificate/{employee}', [DocumentController::class, 'salaryCertificate'])
        ->name('documents.salary-certificate');
});

// Inclure les routes pour la gestion des fournisseurs
require __DIR__.'/fournisseurs.php';

