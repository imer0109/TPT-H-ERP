<?php

use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AccountingDashboardController;
use App\Http\Controllers\AccountingExportController;
use App\Http\Controllers\AccountingJournalController;
use App\Http\Controllers\AccountingReportController;
use App\Http\Controllers\AccountingSettingsController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\ApiConnectorController;
use App\Http\Controllers\ApiDataMappingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BiometricAttendanceController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\CashSessionController;
use App\Http\Controllers\CashTransactionController;
use App\Http\Controllers\ChartOfAccountsController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientIntegrationController;
use App\Http\Controllers\ClientInteractionController;
use App\Http\Controllers\ClientReclamationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyDashboardController;
use App\Http\Controllers\CompanyPolicyController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeAssignmentController;
use App\Http\Controllers\EmployeeContractController;

use App\Http\Controllers\EmployeeEvaluationController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HR\HrDashboardController;
use App\Http\Controllers\HR\HrReportController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LoyaltyCardController;
use App\Http\Controllers\OperationalAgentDashboardController;
use App\Http\Controllers\PayrollItemController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PurchaseDashboardController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StockAlertController;
use App\Http\Controllers\StockInventoryController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StockProductController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\Supplier\SupplierDashboardController;
use App\Http\Controllers\Supplier\SupplierDeliveryController;
use App\Http\Controllers\Supplier\SupplierDocumentController;
use App\Http\Controllers\Supplier\SupplierIntegrationController;
use App\Http\Controllers\Supplier\SupplierIssueController;
use App\Http\Controllers\Supplier\SupplierOrderController;
use App\Http\Controllers\Supplier\SupplierPaymentController;
use App\Http\Controllers\Supplier\SupplierPortalController;
use App\Http\Controllers\SupplierContractController;
use App\Http\Controllers\SupplierOrderController as PurchaseSupplierOrderController;
use App\Http\Controllers\SupplierRatingController;
use App\Http\Controllers\TaxRegulationController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TransactionNatureController;
use App\Http\Controllers\TwoFactorAuthController;
use App\Http\Controllers\UserAssignmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserSessionController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\ViewerDashboardController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\BladeTestController;
use App\Http\Controllers\DashboardTestController;
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
    // Supplier Portal Routes (Moved to line 479)


    // Routes pour les sociétés with entity access control
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/{company}', [CompanyController::class, 'show'])->name('companies.show');
        Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::put('/{company}', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        Route::post('/{company}/archive', [CompanyController::class, 'archive'])->name('companies.archive');
        Route::post('/{company}/duplicate', [CompanyController::class, 'duplicate'])->name('companies.duplicate');
    });

    // Dashboard routes for companies
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard/{id}', [CompanyDashboardController::class, 'company'])->name('dashboard.company');
    });

    // Routes pour les agences with entity access control
    Route::prefix('agencies')->group(function () {
        Route::get('/', [AgencyController::class, 'index'])->name('agencies.index');
        Route::get('/create', [AgencyController::class, 'create'])->name('agencies.create');
        Route::post('/', [AgencyController::class, 'store'])->name('agencies.store');
        Route::get('/{agency}', [AgencyController::class, 'show'])->name('agencies.show');
        Route::get('/{agency}/edit', [AgencyController::class, 'edit'])->name('agencies.edit');
        Route::put('/{agency}', [AgencyController::class, 'update'])->name('agencies.update');
        Route::delete('/{agency}', [AgencyController::class, 'destroy'])->name('agencies.destroy');
        Route::post('/{agency}/archive', [AgencyController::class, 'archive'])->name('agencies.archive');
        Route::post('/{agency}/duplicate', [AgencyController::class, 'duplicate'])->name('agencies.duplicate');
    });

    // Agency dashboard routes with entity access control
    Route::get('companies/dashboard/agency/{id}', [CompanyDashboardController::class, 'agency'])->name('companies.dashboard.agency');

    // Routes for audit trails
    Route::prefix('audit-trails')->name('audit-trails.')->group(function () {
        Route::get('/', [AuditTrailController::class, 'index'])->name('index');
        Route::get('/company/{id}', [AuditTrailController::class, 'showCompanyTrails'])->name('company');
        Route::get('/agency/{id}', [AuditTrailController::class, 'showAgencyTrails'])->name('agency');
    });

    // Routes for user sessions
    Route::prefix('user-sessions')->name('user-sessions.')->group(function () {
        Route::get('/', [UserSessionController::class, 'index'])->name('index');
        Route::get('/{id}', [UserSessionController::class, 'show'])->name('show');
        Route::delete('/{id}', [UserSessionController::class, 'destroy'])->name('destroy');
    });

    // Routes for entity-specific parameters with access control
    Route::middleware(['permission:companies.bank_accounts.view'])->group(function () {
        Route::resource('bank-accounts', BankAccountController::class);
    });
    Route::middleware(['permission:companies.policies.view'])->group(function () {
        Route::resource('policies', CompanyPolicyController::class);
    });
    Route::middleware(['permission:companies.tax_regulations.view'])->group(function () {
        Route::resource('tax-regulations', TaxRegulationController::class);
    });

    Route::resource('roles', RoleController::class)->middleware('auth');

    // New routes for teams and departments
    Route::resource('teams', TeamController::class)->middleware('auth');
    Route::resource('departments', DepartmentController::class)->middleware('auth');

    // Routes for 2FA
    Route::prefix('2fa')->name('2fa.')->group(function () {
        Route::get('/setup', [TwoFactorAuthController::class, 'showSetupForm'])->name('setup');
        Route::post('/enable', [TwoFactorAuthController::class, 'enable2FA'])->name('enable');
        Route::post('/disable', [TwoFactorAuthController::class, 'disable2FA'])->name('disable');
        Route::get('/recovery-codes', [TwoFactorAuthController::class, 'showRecoveryCodes'])->name('recovery');
        Route::post('/regenerate-recovery-codes', [TwoFactorAuthController::class, 'regenerateRecoveryCodes'])->name('regenerate-recovery');
    });

    // Routes for user assignments
    Route::prefix('user-assignments')->name('user-assignments.')->group(function () {
        Route::get('/', [UserAssignmentController::class, 'index'])->name('index');
        Route::get('/{user}/assign', [UserAssignmentController::class, 'showAssignments'])->name('assign');
        Route::post('/{user}/company', [UserAssignmentController::class, 'assignToCompany'])->name('assign.company');
        Route::post('/{user}/agency', [UserAssignmentController::class, 'assignToAgency'])->name('assign.agency');
        Route::post('/{user}/team', [UserAssignmentController::class, 'assignToTeam'])->name('assign.team');
        Route::post('/{user}/department', [UserAssignmentController::class, 'assignToDepartment'])->name('assign.department');
        Route::post('/{user}/manager', [UserAssignmentController::class, 'assignManager'])->name('assign.manager');
        Route::delete('/{user}/company/{companyId}', [UserAssignmentController::class, 'removeCompanyAssignment'])->name('remove.company');
        Route::delete('/user/agency/{agencyId}', [UserAssignmentController::class, 'removeAgencyAssignment'])->name('remove.agency');
    });
});

// Route::get('/', function () {
//     return view('dashboard');
// })->middleware('auth');

// Remplace par le bon modèle si nécessaire

Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

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
    Route::post('transfers/{transfer}/validate', [StockTransferController::class, 'validateTransfer'])->name('transfers.validate');
    Route::post('transfers/{transfer}/receive', [StockTransferController::class, 'receive'])->name('transfers.receive');
    Route::post('transfers/{transfer}/cancel', [StockTransferController::class, 'cancel'])->name('transfers.cancel');

    // Alertes
    Route::resource('alerts', StockAlertController::class);
    Route::post('alerts/{alert}/toggle-status', [StockAlertController::class, 'toggleStatus'])->name('alerts.toggle-status');
    Route::post('alerts/{alert}/toggle-notifications', [StockAlertController::class, 'toggleNotifications'])->name('alerts.toggle-notifications');

    // Inventaires
    Route::resource('inventories', StockInventoryController::class);
    Route::post('inventories/{inventory}/validate', [StockInventoryController::class, 'validateInventory'])->name('inventories.validate');
    Route::get('inventories/{inventory}/pdf', [StockInventoryController::class, 'generatePdf'])->name('inventories.pdf');

    // Rapports
    Route::get('reports/current-stock', [StockReportController::class, 'currentStock'])->name('reports.current-stock');
    Route::get('reports/movements-history', [StockReportController::class, 'movementsHistory'])->name('reports.movements-history');
    Route::get('reports/valuation', [StockReportController::class, 'valuation'])->name('reports.valuation');
    Route::get('reports/losses', [StockReportController::class, 'losses'])->name('reports.losses');

    // Produits (stock)
    Route::resource('products', StockProductController::class);
});

// Routes pour la gestion des clients
Route::middleware(['auth'])->group(function () {
    // Dashboard clients
    Route::get('clients/dashboard', [ClientController::class, 'dashboard'])->name('clients.dashboard');

    // Export clients
    Route::get('clients/export', [ClientController::class, 'export'])->name('clients.export');

    // Routes pour les clients
    Route::resource('clients', ClientController::class);
    Route::get('clients/{client}/transactions', [ClientController::class, 'transactions'])->name('clients.transactions');
    Route::get('clients/{client}/transactions/export', [ClientController::class, 'exportTransactions'])->name('clients.transactions.export');
    Route::get('clients/segments', [ClientController::class, 'segments'])->name('clients.segments');
    Route::get('clients/segments/export', [ClientController::class, 'exportSegment'])->name('clients.segments.export');
    Route::get('clients/{client}/score', [ClientController::class, 'score'])->name('clients.score');

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

    // Routes pour les intégrations clients
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('integrations', [ClientIntegrationController::class, 'index'])->name('integrations.index');
        Route::get('integrations/client/{client}/create', [ClientIntegrationController::class, 'create'])->name('integrations.create');
        Route::post('integrations/client/{client}', [ClientIntegrationController::class, 'store'])->name('integrations.store');
        Route::get('integrations/{integration}/edit', [ClientIntegrationController::class, 'edit'])->name('integrations.edit');
        Route::put('integrations/{integration}', [ClientIntegrationController::class, 'update'])->name('integrations.update');
        Route::delete('integrations/{integration}', [ClientIntegrationController::class, 'destroy'])->name('integrations.destroy');
        Route::post('integrations/{integration}/sync', [ClientIntegrationController::class, 'sync'])->name('integrations.sync');
        Route::post('integrations/export-segment', [ClientIntegrationController::class, 'exportSegment'])->name('integrations.export-segment');
    });

    // Routes pour les cartes de fidélité
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('loyalty', [LoyaltyCardController::class, 'index'])->name('loyalty.index');
        Route::get('loyalty/dashboard', [LoyaltyCardController::class, 'dashboard'])->name('loyalty.dashboard');
        Route::get('loyalty/client/{client}/create', [LoyaltyCardController::class, 'create'])->name('loyalty.create');
        Route::post('loyalty/client/{client}', [LoyaltyCardController::class, 'store'])->name('loyalty.store');
        Route::get('loyalty/{loyaltyCard}/edit', [LoyaltyCardController::class, 'edit'])->name('loyalty.edit');
        Route::put('loyalty/{loyaltyCard}', [LoyaltyCardController::class, 'update'])->name('loyalty.update');
        Route::delete('loyalty/{loyaltyCard}', [LoyaltyCardController::class, 'destroy'])->name('loyalty.destroy');
        Route::post('loyalty/{loyaltyCard}/add-points', [LoyaltyCardController::class, 'addPoints'])->name('loyalty.add-points');
        Route::post('loyalty/{loyaltyCard}/redeem-points', [LoyaltyCardController::class, 'redeemPoints'])->name('loyalty.redeem-points');
    });
});

// Routes pour la gestion du personnel
Route::prefix('hr')->name('hr.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', [HrDashboardController::class, 'index'])->name('dashboard');
    
    // Employés
    Route::resource('employees', \App\Http\Controllers\HR\EmployeeController::class);
    
    Route::prefix('employees/{employee}/assignments')->name('employees.assignments.')->group(function () {
        Route::get('create', [EmployeeAssignmentController::class, 'create'])->name('create');
        Route::post('/', [EmployeeAssignmentController::class, 'store'])->name('store');
        Route::get('{assignment}/edit', [EmployeeAssignmentController::class, 'edit'])->name('edit');
        Route::put('{assignment}', [EmployeeAssignmentController::class, 'update'])->name('update');
        Route::delete('{assignment}', [EmployeeAssignmentController::class, 'destroy'])->name('destroy');
    });

    // Contracts
    Route::resource('contracts', ContractController::class);
    Route::post('contracts/{contract}/terminate', [ContractController::class, 'terminate'])->name('contracts.terminate');
    Route::post('contracts/{contract}/renew', [ContractController::class, 'renew'])->name('contracts.renew');
    Route::post('contracts/{contract}/activate', [ContractController::class, 'activate'])->name('contracts.activate');
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
    Route::get('attendances/biometric', function () {
        return view('attendances.biometric');
    })->name('attendances.biometric');

    // Biometric Attendance API
    Route::prefix('api/biometric')->name('api.biometric.')->group(function () {
        Route::post('attendance', [BiometricAttendanceController::class, 'handleBiometricData'])->name('attendance');
        Route::get('employees', [BiometricAttendanceController::class, 'syncEmployees'])->name('employees.sync');
        Route::get('device/{deviceId}/attendance', [BiometricAttendanceController::class, 'getDeviceAttendance'])->name('device.attendance');
    });

    // Paie
    Route::resource('payroll-items', PayrollItemController::class);
    Route::resource('payslips', PayslipController::class);
    Route::post('payslips/generate', [PayslipController::class, 'generate'])->name('payslips.generate');
    Route::post('payslips/{payslip}/validate', [PayslipController::class, 'validatePayslip'])->name('payslips.validate');
    Route::post('payslips/{payslip}/pay', [PayslipController::class, 'pay'])->name('payslips.pay');
    Route::get('payslips/{payslip}/download', [PayslipController::class, 'download'])->name('payslips.download');

    // Évaluations
    Route::resource('evaluations', EvaluationController::class);
    Route::post('evaluations/{evaluation}/submit', [EvaluationController::class, 'submit'])->name('evaluations.submit');
    Route::post('evaluations/{evaluation}/acknowledge', [EvaluationController::class, 'acknowledge'])->name('evaluations.acknowledge');
    Route::post('evaluations/{evaluation}/dispute', [EvaluationController::class, 'dispute'])->name('evaluations.dispute');

    // Postes
    Route::resource('positions', PositionController::class);
    Route::get('positions/organizational-chart', [PositionController::class, 'organizationalChart'])->name('positions.organizational-chart');

    // Rapports RH
    Route::get('reports/dashboard', [HrReportController::class, 'dashboard'])->name('reports.dashboard');
    Route::get('reports/headcount', [HrReportController::class, 'headcount'])->name('reports.headcount');
    Route::get('reports/turnover', [HrReportController::class, 'turnover'])->name('reports.turnover');
    Route::get('reports/payroll', [HrReportController::class, 'payroll'])->name('reports.payroll');
    Route::get('reports/leave', [HrReportController::class, 'leave'])->name('reports.leave');
    Route::get('reports/attendance', [HrReportController::class, 'attendance'])->name('reports.attendance');

    // Documents
    Route::get('documents/work-certificate/{employee}', [DocumentController::class, 'workCertificate'])
        ->name('documents.work-certificate');
    Route::post('documents/work-certificate/{employee}', [DocumentController::class, 'generateWorkCertificate'])
        ->name('documents.work-certificate.generate');
    Route::get('documents/salary-certificate/{employee}', [DocumentController::class, 'salaryCertificate'])
        ->name('documents.salary-certificate');
    Route::post('documents/salary-certificate/{employee}', [DocumentController::class, 'generateSalaryCertificate'])
        ->name('documents.salary-certificate.generate');
    Route::get('documents', [DocumentController::class, 'index'])->name('hr.documents.index');
});

// Routes pour la gestion des fournisseurs (fusionnées ici)
Route::middleware(['auth'])->group(function () {
    Route::get('/fournisseurs/export', [FournisseurController::class, 'export'])->name('fournisseurs.export');
    Route::resource('fournisseurs', FournisseurController::class);

    Route::prefix('fournisseurs')->name('fournisseurs.')->group(function () {
        Route::get('dashboard', [SupplierDashboardController::class, 'index'])->name('dashboard');
        Route::get('commandes', [SupplierOrderController::class, 'index'])->name('orders.index');
        Route::get('commandes/create', [SupplierOrderController::class, 'create'])->name('orders.create');
        Route::post('commandes', [SupplierOrderController::class, 'store'])->name('orders.store');
        Route::get('commandes/{order}', [SupplierOrderController::class, 'show'])->name('orders.show');
        Route::get('commandes-export', [SupplierOrderController::class, 'exportCsv'])->name('orders.export');
        Route::get('commandes/{order}/pdf', [SupplierOrderController::class, 'pdf'])->name('orders.pdf');
        Route::get('livraisons', [SupplierDeliveryController::class, 'index'])->name('deliveries.index');
        Route::get('livraisons/create', [SupplierDeliveryController::class, 'create'])->name('deliveries.create');
        Route::post('livraisons', [SupplierDeliveryController::class, 'store'])->name('deliveries.store');
        Route::get('paiements', [SupplierPaymentController::class, 'index'])->name('payments.index');
        Route::get('paiements/create', [SupplierPaymentController::class, 'create'])->name('payments.create');
        Route::post('paiements', [SupplierPaymentController::class, 'store'])->name('payments.store');
        Route::get('reclamations', [SupplierIssueController::class, 'index'])->name('issues.index');
        Route::get('reclamations/create', [SupplierIssueController::class, 'create'])->name('issues.create');
        Route::post('reclamations', [SupplierIssueController::class, 'store'])->name('issues.store');

        // Supplier Ratings
        Route::prefix('{fournisseur}/ratings')->name('ratings.')->group(function () {
            Route::get('/', [SupplierRatingController::class, 'index'])->name('index');
            Route::get('create', [SupplierRatingController::class, 'create'])->name('create');
            Route::post('/', [SupplierRatingController::class, 'store'])->name('store');
            Route::get('{rating}', [SupplierRatingController::class, 'show'])->name('show');
            Route::get('{rating}/edit', [SupplierRatingController::class, 'edit'])->name('edit');
            Route::put('{rating}', [SupplierRatingController::class, 'update'])->name('update');
            Route::delete('{rating}', [SupplierRatingController::class, 'destroy'])->name('destroy');
            Route::post('auto-evaluate', [SupplierRatingController::class, 'autoEvaluate'])->name('auto-evaluate');
        });

        // Supplier Documents
        Route::prefix('{fournisseur}/documents')->name('documents.')->group(function () {
            Route::get('/', [SupplierDocumentController::class, 'index'])->name('index');
            Route::get('/create', [SupplierDocumentController::class, 'create'])->name('create');
            Route::post('/', [SupplierDocumentController::class, 'store'])->name('store');
            Route::get('/{document}', [SupplierDocumentController::class, 'show'])->name('show');
            Route::get('/{document}/edit', [SupplierDocumentController::class, 'edit'])->name('edit');
            Route::put('/{document}', [SupplierDocumentController::class, 'update'])->name('update');
            Route::delete('/{document}', [SupplierDocumentController::class, 'destroy'])->name('destroy');
            Route::get('/{document}/download', [SupplierDocumentController::class, 'download'])->name('download');
            Route::get('/{document}/view', [SupplierDocumentController::class, 'view'])->name('view');
        });

        // Supplier Contracts
        Route::prefix('contracts')->name('contracts.')->group(function () {
            Route::get('/', [SupplierContractController::class, 'index'])->name('index');
            Route::get('/create', [SupplierContractController::class, 'create'])->name('create');
            Route::post('/', [SupplierContractController::class, 'store'])->name('store');
            Route::get('/{contract}', [SupplierContractController::class, 'show'])->name('show');
            Route::get('/{contract}/edit', [SupplierContractController::class, 'edit'])->name('edit');
            Route::put('/{contract}', [SupplierContractController::class, 'update'])->name('update');
            Route::delete('/{contract}', [SupplierContractController::class, 'destroy'])->name('destroy');
            Route::post('/{contract}/terminate', [SupplierContractController::class, 'terminate'])->name('terminate');
            Route::post('/{contract}/renew', [SupplierContractController::class, 'renew'])->name('renew');
        });
    });
});

// Routes pour le portail fournisseur
Route::prefix('supplier/portal')->name('supplier.portal.')->middleware(['auth'])->group(function () {
    Route::get('/', [SupplierPortalController::class, 'index'])->name('index');
    Route::get('profile', [SupplierPortalController::class, 'profile'])->name('profile');
    Route::put('profile', [SupplierPortalController::class, 'updateProfile'])->name('update-profile');

    // Commandes
    Route::get('orders', [SupplierPortalController::class, 'orders'])->name('orders');
    Route::get('orders/{order}', [SupplierPortalController::class, 'showOrder'])->name('orders.show');

    // Livraisons
    Route::get('deliveries', [SupplierPortalController::class, 'deliveries'])->name('deliveries');
    Route::get('deliveries/{delivery}', [SupplierPortalController::class, 'showDelivery'])->name('deliveries.show');

    // Factures
    Route::get('invoices', [SupplierPortalController::class, 'invoices'])->name('invoices');
    Route::get('invoices/{invoice}', [SupplierPortalController::class, 'showInvoice'])->name('invoices.show');

    // Paiements
    Route::get('payments', [SupplierPortalController::class, 'payments'])->name('payments');
    Route::get('payments/{payment}', [SupplierPortalController::class, 'showPayment'])->name('payments.show');

    // Contrats
    Route::get('contracts', [SupplierPortalController::class, 'contracts'])->name('contracts');
    Route::get('contracts/{contract}', [SupplierPortalController::class, 'showContract'])->name('contracts.show');

    // Réclamations
    Route::get('issues', [SupplierPortalController::class, 'issues'])->name('issues');
    Route::get('issues/create', [SupplierPortalController::class, 'createIssue'])->name('create-issue');
    Route::post('issues', [SupplierPortalController::class, 'storeIssue'])->name('store-issue');
    Route::get('issues/{issue}', [SupplierPortalController::class, 'showIssue'])->name('issues.show');

    // Intégrations
    Route::get('integrations', [SupplierIntegrationController::class, 'index'])->name('integrations.index');
    Route::get('integrations/create', [SupplierIntegrationController::class, 'create'])->name('integrations.create');
    Route::post('integrations', [SupplierIntegrationController::class, 'store'])->name('integrations.store');
    Route::get('integrations/{integration}', [SupplierIntegrationController::class, 'show'])->name('integrations.show');
    Route::get('integrations/{integration}/edit', [SupplierIntegrationController::class, 'edit'])->name('integrations.edit');
    Route::put('integrations/{integration}', [SupplierIntegrationController::class, 'update'])->name('integrations.update');
    Route::delete('integrations/{integration}', [SupplierIntegrationController::class, 'destroy'])->name('integrations.destroy');
    Route::post('integrations/{integration}/sync', [SupplierIntegrationController::class, 'sync'])->name('integrations.sync');
});

// Routes pour la gestion des achats
Route::prefix('purchases')->name('purchases.')->middleware(['auth'])->group(function () {
    // Dashboard des achats
    Route::get('/dashboard', [PurchaseDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [PurchaseSupplierOrderController::class, 'analytics'])->name('analytics');

    // Demandes d'achat (DA)
    Route::resource('requests', PurchaseRequestController::class);
    Route::post('requests/{request}/validate', [PurchaseRequestController::class, 'validateRequest'])->name('requests.validate');
    Route::post('requests/{request}/convert-to-boc', [PurchaseRequestController::class, 'convertToBoc'])->name('requests.convert-to-boc');
    Route::post('requests/{request}/upload-file', [PurchaseRequestController::class, 'uploadFile'])->name('requests.upload-file');
    Route::get('requests/export', [PurchaseRequestController::class, 'export'])->name('requests.export');

    // Bons de commande (BOC)
    Route::resource('orders', PurchaseSupplierOrderController::class);
    Route::post('orders/{order}/update-status', [PurchaseSupplierOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders/{order}/pdf', [PurchaseSupplierOrderController::class, 'generatePdf'])->name('orders.pdf');
    Route::get('orders/{order}/create-delivery', [PurchaseSupplierOrderController::class, 'createDelivery'])->name('orders.create-delivery');
    Route::get('orders/{order}/create-payment', [PurchaseSupplierOrderController::class, 'createPayment'])->name('orders.create-payment');

    // Réceptions et livraisons
    Route::resource('deliveries', SupplierDeliveryController::class);
    Route::post('deliveries/{delivery}/validate', [SupplierDeliveryController::class, 'validateDelivery'])->name('deliveries.validate');
    Route::post('deliveries/{delivery}/receive', [SupplierDeliveryController::class, 'receive'])->name('deliveries.receive');

    // Factures et paiements
    Route::resource('payments', SupplierPaymentController::class);
    Route::post('payments/{payment}/validate', [SupplierPaymentController::class, 'validatePayment'])->name('payments.validate');
    Route::get('payments/export', [SupplierPaymentController::class, 'export'])->name('payments.export');

    // Réclamations et litiges
    Route::resource('issues', SupplierIssueController::class);
    Route::post('issues/{issue}/resolve', [SupplierIssueController::class, 'resolve'])->name('issues.resolve');
});

// Routes pour la gestion de la comptabilité
Route::prefix('accounting')->name('accounting.')->middleware(['auth'])->group(function () {
    // Dashboard comptable
    Route::get('/dashboard', [AccountingController::class, 'dashboard'])->name('dashboard');

    // Plan comptable
    Route::resource('chart-of-accounts', ChartOfAccountsController::class);
    Route::get('chart-of-accounts/tree/view', [ChartOfAccountsController::class, 'tree'])->name('chart-of-accounts.tree');
    Route::get('chart-of-accounts/import/form', [ChartOfAccountsController::class, 'importForm'])->name('chart-of-accounts.import.form');
    Route::post('chart-of-accounts/import', [ChartOfAccountsController::class, 'import'])->name('chart-of-accounts.import');
    Route::get('chart-of-accounts/export', [ChartOfAccountsController::class, 'export'])->name('chart-of-accounts.export');
    Route::post('chart-of-accounts/syscohada', [ChartOfAccountsController::class, 'createSyscohadaPlan'])->name('chart-of-accounts.syscohada');

    // Journaux comptables
    Route::resource('journals', AccountingJournalController::class);
    Route::post('journals/{journal}/entries', [AccountingJournalController::class, 'createEntry'])->name('journals.create-entry');
    Route::get('journals/{journal}/balance', [AccountingJournalController::class, 'balance'])->name('journals.balance');

    // Écritures comptables
    Route::get('entries', [AccountingController::class, 'entries'])->name('entries.index');
    Route::get('entries/create', [AccountingController::class, 'createEntry'])->name('entries.create');
    Route::post('entries', [AccountingController::class, 'storeEntry'])->name('entries.store');
    Route::get('entries/{entry}', [AccountingController::class, 'showEntry'])->name('entries.show');
    Route::post('entries/{entry}/validate', [AccountingController::class, 'validateEntry'])->name('entries.validate');
    Route::get('entries/{entry}/edit', [AccountingController::class, 'editEntry'])->name('entries.edit');
    Route::put('entries/{entry}', [AccountingController::class, 'updateEntry'])->name('entries.update');
    Route::delete('entries/{entry}', [AccountingController::class, 'destroyEntry'])->name('entries.destroy');

    // Balance et grand livre
    Route::get('balance', [AccountingController::class, 'balance'])->name('balance');
    Route::get('trial-balance', [AccountingController::class, 'trialBalance'])->name('trial-balance');
    Route::get('general-ledger', [AccountingController::class, 'generalLedger'])->name('general-ledger');

    // États financiers et rapports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AccountingReportController::class, 'index'])->name('index');
        Route::get('general-ledger', [AccountingReportController::class, 'generalLedger'])->name('general-ledger');
        Route::get('trial-balance', [AccountingReportController::class, 'trialBalance'])->name('trial-balance');
        Route::get('income-statement', [AccountingReportController::class, 'incomeStatement'])->name('income-statement');
        Route::get('balance-sheet', [AccountingReportController::class, 'balanceSheet'])->name('balance-sheet');
        Route::get('journal', [AccountingReportController::class, 'journalReport'])->name('journal');
        Route::get('analytical', [AccountingReportController::class, 'analyticalReport'])->name('analytical');
    });

    // Exports comptables
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('excel', [AccountingExportController::class, 'exportExcel'])->name('excel');
        Route::get('sage', [AccountingExportController::class, 'exportSage'])->name('sage');
        Route::get('ebp', [AccountingExportController::class, 'exportEbp'])->name('ebp');
        Route::get('fec', [AccountingExportController::class, 'exportFec'])->name('fec');
        Route::get('chart-of-accounts', [AccountingExportController::class, 'exportChartOfAccounts'])->name('chart-of-accounts');
        Route::get('trial-balance-pdf', [AccountingExportController::class, 'exportTrialBalancePdf'])->name('trial-balance-pdf');
        Route::get('general-ledger-pdf', [AccountingExportController::class, 'exportGeneralLedgerPdf'])->name('general-ledger-pdf');
    });

    // Paramètres comptables
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AccountingSettingsController::class, 'index'])->name('index');
        Route::get('parameters', [AccountingSettingsController::class, 'parameters'])->name('parameters');
        Route::put('parameters', [AccountingSettingsController::class, 'updateParameters'])->name('parameters.update');

        // Cost Centers
        Route::get('cost-centers', [AccountingSettingsController::class, 'costCenters'])->name('cost-centers');
        Route::post('cost-centers', [AccountingSettingsController::class, 'storeCostCenter'])->name('cost-centers.store');
        Route::put('cost-centers/{costCenter}', [AccountingSettingsController::class, 'updateCostCenter'])->name('cost-centers.update');
        Route::delete('cost-centers/{costCenter}', [AccountingSettingsController::class, 'destroyCostCenter'])->name('cost-centers.destroy');

        // Projects
        Route::get('projects', [AccountingSettingsController::class, 'projects'])->name('projects');
        Route::post('projects', [AccountingSettingsController::class, 'storeProject'])->name('projects.store');
        Route::put('projects/{project}', [AccountingSettingsController::class, 'updateProject'])->name('projects.update');
        Route::delete('projects/{project}', [AccountingSettingsController::class, 'destroyProject'])->name('projects.destroy');

        // Journals
        Route::get('journals', [AccountingSettingsController::class, 'journals'])->name('journals');
        Route::post('journals', [AccountingSettingsController::class, 'storeJournal'])->name('journals.store');
        Route::put('journals/{journal}', [AccountingSettingsController::class, 'updateJournal'])->name('journals.update');
        Route::delete('journals/{journal}', [AccountingSettingsController::class, 'destroyJournal'])->name('journals.destroy');

        // Import/Export
        Route::post('import-chart-of-accounts', [AccountingSettingsController::class, 'importChartOfAccounts'])->name('import-chart-of-accounts');
        Route::post('reset-chart-of-accounts', [AccountingSettingsController::class, 'resetChartOfAccounts'])->name('reset-chart-of-accounts');
    });

    // Clôtures
    Route::get('monthly-closing', [AccountingController::class, 'monthlyClosing'])->name('monthly-closing');
    Route::get('yearly-closing', [AccountingController::class, 'yearlyClosing'])->name('yearly-closing');
    Route::post('close-month', [AccountingController::class, 'closeMonth'])->name('close-month');
    Route::post('close-year', [AccountingController::class, 'closeYear'])->name('close-year');
});

// Routes pour l'intégration API
Route::prefix('api-connectors')->name('api-connectors.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [ApiConnectorController::class, 'dashboard'])->name('dashboard');

    // Connector management
    Route::resource('connectors', ApiConnectorController::class)->names([
        'index' => 'api-connectors.index',
        'create' => 'api-connectors.create',
        'store' => 'api-connectors.store',
        'show' => 'api-connectors.show',
        'edit' => 'api-connectors.edit',
        'update' => 'api-connectors.update',
        'destroy' => 'api-connectors.destroy',
    ])->parameters(['connector' => 'apiConnector']);
    Route::post('/{apiConnector}/test-connection', [ApiConnectorController::class, 'testConnection'])->name('test-connection');
    Route::post('/{apiConnector}/sync-now', [ApiConnectorController::class, 'syncNow'])->name('sync-now');
    Route::post('/{apiConnector}/toggle-status', [ApiConnectorController::class, 'toggleStatus'])->name('toggle-status');
    Route::get('/{apiConnector}/logs', [ApiConnectorController::class, 'logs'])->name('logs');
    Route::get('/{apiConnector}/export-config', [ApiConnectorController::class, 'exportConfig'])->name('export-config');
    Route::post('/import-config', [ApiConnectorController::class, 'importConfig'])->name('import-config');

    // Data mappings
    Route::prefix('{apiConnector}/mappings')->name('mappings.')->group(function () {
        Route::get('/', [ApiDataMappingController::class, 'index'])->name('index');
        Route::get('/create', [ApiDataMappingController::class, 'create'])->name('create');
        Route::post('/', [ApiDataMappingController::class, 'store'])->name('store');
        Route::get('/{mapping}', [ApiDataMappingController::class, 'show'])->name('show');
        Route::get('/{mapping}/edit', [ApiDataMappingController::class, 'edit'])->name('edit');
        Route::put('/{mapping}', [ApiDataMappingController::class, 'update'])->name('update');
        Route::delete('/{mapping}', [ApiDataMappingController::class, 'destroy'])->name('destroy');
        Route::post('/suggestions', [ApiDataMappingController::class, 'suggestions'])->name('suggestions');
        Route::post('/{mapping}/test-transformation', [ApiDataMappingController::class, 'testTransformation'])->name('test-transformation');
        Route::post('/bulk-create', [ApiDataMappingController::class, 'bulkCreate'])->name('bulk-create');
        Route::get('/export', [ApiDataMappingController::class, 'export'])->name('export');
        Route::post('/import', [ApiDataMappingController::class, 'import'])->name('import');
    });
});

// Routes for validation workflows and requests
Route::prefix('validations')->name('validations.')->middleware(['auth'])->group(function () {
    // Workflows
    Route::resource('workflows', ValidationController::class);

    // Requests
    Route::get('requests', [ValidationController::class, 'requests'])->name('requests.index');
    Route::get('requests/{request}', [ValidationController::class, 'showRequest'])->name('requests.show');
    Route::post('requests/{request}/approve', [ValidationController::class, 'approveRequest'])->name('requests.approve');
    Route::post('requests/{request}/reject', [ValidationController::class, 'rejectRequest'])->name('requests.reject');
    Route::post('requests/{request}/delegate', [ValidationController::class, 'delegateRequest'])->name('requests.delegate');
});

// Test route for validation system
Route::get('/test-validation', function () {
    // Create a test validation workflow
    $workflow = \App\Models\ValidationWorkflow::create([
        'name' => 'Test Workflow',
        'description' => 'Workflow de test pour décaissements',
        'module' => 'accounting',
        'entity_type' => 'App\Models\AccountingEntry',
        'company_id' => 1,
        'conditions' => json_encode([
            [
                'field' => 'amount',
                'operator' => 'greater_than',
                'value' => 100000,
            ],
        ]),
        'steps' => json_encode([
            [
                'name' => 'Validation Chef',
                'description' => 'Validation par le chef de service',
                'role' => 'chef_service',
                'timeout_hours' => 24,
            ],
            [
                'name' => 'Validation DG',
                'description' => 'Validation par le Directeur Général',
                'role' => 'directeur_general',
                'timeout_hours' => 48,
            ],
        ]),
        'is_active' => true,
        'created_by' => 1,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Validation workflow created successfully',
        'workflow' => $workflow,
    ]);
})->middleware('auth');

// Test route to verify validation views
Route::get('/test-validation-views', function () {
    // Test that the views load without errors
    try {
        // Test workflow index view
        $workflows = \App\Models\ValidationWorkflow::paginate(15);

        return view('validations.workflows.index', compact('workflows'));
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});

// Routes pour la gestion de la caisse
Route::prefix('cash')->name('cash.')->middleware(['auth'])->group(function () {
    // Caisses
    Route::resource('registers', CashRegisterController::class)->parameters([
        'registers' => 'cashRegister',
    ]);
    Route::post('registers/{cashRegister}/open', [CashSessionController::class, 'open'])->name('registers.open');

    // Sessions de caisse
    Route::prefix('registers/{cashRegister}/sessions')->name('sessions.')->group(function () {
        Route::post('{session}/close', [CashSessionController::class, 'close'])->name('close');
        Route::get('{session}/report', [CashSessionController::class, 'report'])->name('report');
    });

    // Transactions
    Route::resource('transactions', CashTransactionController::class);
    Route::get('registers/{cashRegister}/transactions/create', [CashTransactionController::class, 'create'])->name('registers.transactions.create');
    Route::post('registers/{cashRegister}/transactions', [CashTransactionController::class, 'store'])->name('registers.transactions.store');
    Route::post('transactions/{transaction}/validate', [CashTransactionController::class, 'validateTransaction'])->name('transactions.validate');
    Route::post('transactions/{transaction}/approve', [CashTransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('transactions/{transaction}/reject', [CashTransactionController::class, 'reject'])->name('transactions.reject');

    // Natures d'opérations
    Route::resource('natures', TransactionNatureController::class);

    // Rapports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('cash-journal', [CashTransactionController::class, 'index'])->name('cash-journal');
        Route::get('cash-journal/export', [CashTransactionController::class, 'export'])->name('cash-journal.export');
        Route::get('consolidated-movements', [CashTransactionController::class, 'consolidatedMovements'])->name('consolidated-movements');
        Route::get('consolidated-movements/export', [CashTransactionController::class, 'exportConsolidatedMovements'])->name('consolidated-movements.export');
    });

    // Dashboard
    Route::get('dashboard', [CashRegisterController::class, 'dashboard'])->name('dashboard');
});

// Routes pour la gestion des profils utilisateurs
Route::middleware(['auth'])->group(function () {
    Route::get('/user-profiles/export', [UserProfileController::class, 'export'])->name('user-profiles.export');
    Route::resource('user-profiles', UserProfileController::class);
});

// Route for viewer dashboard
Route::get('/viewer/dashboard', [ViewerDashboardController::class, 'index'])->name('viewer.dashboard');

// Routes for other dashboards
Route::get('/accounting/dashboard', [AccountingDashboardController::class, 'index'])->name('accounting.dashboard');
Route::get('/operational/dashboard', [OperationalAgentDashboardController::class, 'index'])->name('operational.dashboard');

// Routes for services
Route::middleware(['auth'])->group(function () {
    Route::resource('services', ServiceController::class);
});

// Routes de test
Route::get('/test', [TestController::class, 'test'])->name('test.simple');
Route::get('/test-blade', [TestController::class, 'testBlade'])->name('test.blade');
Route::get('/test-no-auth', [TestController::class, 'testBlade'])->name('test.noauth');
Route::get('/test-simple', [TestController::class, 'testSimple'])->name('test.simple.view');
Route::get('/test-layout', [TestController::class, 'testLayout'])->name('test.layout');
Route::get('/test-final', [TestController::class, 'testFinal'])->name('test.final');

// Routes de test Blade
Route::get('/blade-test', [BladeTestController::class, 'testBlade'])->name('blade.test');
Route::get('/blade-test-page', [BladeTestController::class, 'testBladePage'])->name('blade.test.page');

// Routes de test Dashboard
Route::get('/dashboard-test', [DashboardTestController::class, 'testDashboard'])->name('dashboard.test');
Route::get('/dashboard-test-auth', [DashboardTestController::class, 'testDashboard'])->name('dashboard.test.auth')->middleware('auth');
