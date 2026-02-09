<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Agency;
use App\Models\CashTransaction;
use App\Models\EntityAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyDashboardController extends Controller
{
    public function index()
    {
        // Get all companies with their relationships
        $companies = Company::with(['filiales', 'agencies'])->get();
        
        // Get holding companies only
        $holdings = Company::holdings()->with(['filiales', 'agencies'])->get();
        
        // Get active companies
        $activeCompanies = Company::active()->count();
        
        // Get inactive companies
        $inactiveCompanies = Company::where('active', false)->count();
        
        // Get total agencies
        $totalAgencies = Agency::count();
        
        // Get active agencies
        $activeAgencies = Agency::active()->count();
        
        // Get agencies in standby
        $standbyAgencies = Agency::inStandby()->count();
        
        // Get companies by sector
        $companiesBySector = Company::select('secteur_activite')
            ->selectRaw('count(*) as count')
            ->groupBy('secteur_activite')
            ->get();
        
        // Get companies by country
        $companiesByCountry = Company::select('pays')
            ->selectRaw('count(*) as count')
            ->groupBy('pays')
            ->get();
        
        // Get recent activities for global dashboard
        $recentActivities = EntityAuditTrail::with(['user', 'entity'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get alerts for global dashboard
        $alerts = $this->getGlobalAlerts();
        
        return view('companies.dashboard.index', compact(
            'companies',
            'holdings',
            'activeCompanies',
            'inactiveCompanies',
            'totalAgencies',
            'activeAgencies',
            'standbyAgencies',
            'companiesBySector',
            'companiesByCountry',
            'recentActivities',
            'alerts'
        ));
    }
    
    public function company($id)
    {
        $company = Company::with(['filiales', 'agencies', 'parent'])->findOrFail($id);
        
        // Get financial data for the company
        $cashRegisters = $company->cashRegisters;
        $totalBalance = $cashRegisters->sum('current_balance');
        
        // Get agencies for this company
        $agencies = $company->agencies;
        
        // Get financial summary data
        $encaissements = CashTransaction::whereIn('cash_register_id', $cashRegisters->pluck('id'))
            ->where('type', 'encaissement')
            ->sum('montant');
        
        $decaissements = CashTransaction::whereIn('cash_register_id', $cashRegisters->pluck('id'))
            ->where('type', 'decaissement')
            ->sum('montant');
        
        // Calculate additional KPIs
        $netCashFlow = $encaissements - $decaissements;
        $averageTransaction = $cashRegisters->count() > 0 ? $totalBalance / $cashRegisters->count() : 0;
        
        // Get recent transactions
        $recentTransactions = CashTransaction::whereIn('cash_register_id', $cashRegisters->pluck('id'))
            ->with('cashRegister')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get subsidiaries financial data for comparison
        $subsidiariesData = [];
        foreach ($company->filiales as $filiale) {
            $filialeCashRegisters = $filiale->cashRegisters;
            $filialeTotalBalance = $filialeCashRegisters->sum('current_balance');
            $filialeEncaissements = CashTransaction::whereIn('cash_register_id', $filialeCashRegisters->pluck('id'))
                ->where('type', 'encaissement')
                ->sum('montant');
            $filialeDecaissements = CashTransaction::whereIn('cash_register_id', $filialeCashRegisters->pluck('id'))
                ->where('type', 'decaissement')
                ->sum('montant');
                
            $subsidiariesData[] = [
                'name' => $filiale->raison_sociale,
                'balance' => $filialeTotalBalance,
                'encaissements' => $filialeEncaissements,
                'decaissements' => $filialeDecaissements,
                'agencies_count' => $filiale->agencies->count()
            ];
        }
        
        // Get entity-specific parameters
        $bankAccounts = $company->bankAccounts()->limit(5)->get();
        $policies = $company->policies()->limit(5)->get();
        $taxRegulations = $company->taxRegulations()->limit(5)->get();
        
        // Get recent activities for this company
        $recentActivities = $company->auditTrails()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get alerts for this company
        $alerts = $this->getCompanyAlerts($company);
        
        return view('companies.dashboard.company', compact(
            'company',
            'cashRegisters',
            'totalBalance',
            'agencies',
            'encaissements',
            'decaissements',
            'netCashFlow',
            'averageTransaction',
            'recentTransactions',
            'subsidiariesData',
            'bankAccounts',
            'policies',
            'taxRegulations',
            'recentActivities',
            'alerts'
        ));
    }
    
    public function agency($id)
    {
        $agency = Agency::with(['company', 'responsable', 'taxRegulations', 'policies', 'bankAccounts'])->findOrFail($id);
        
        // Get financial data for the agency
        $cashRegisters = $agency->cashRegisters;
        $totalBalance = $cashRegisters->sum('current_balance');
        
        // Get financial summary data
        $encaissements = CashTransaction::whereIn('cash_register_id', $cashRegisters->pluck('id'))
            ->where('type', 'encaissement')
            ->sum('montant');
        
        $decaissements = CashTransaction::whereIn('cash_register_id', $cashRegisters->pluck('id'))
            ->where('type', 'decaissement')
            ->sum('montant');
        
        // Calculate additional KPIs
        $netCashFlow = $encaissements - $decaissements;
        $transactionCount = CashTransaction::whereIn('cash_register_id', $cashRegisters->pluck('id'))->count();
        $averageTransaction = $transactionCount > 0 ? ($encaissements + $decaissements) / $transactionCount : 0;
        
        // Get recent transactions
        $recentTransactions = CashTransaction::whereIn('cash_register_id', $cashRegisters->pluck('id'))
            ->with('cashRegister')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get entity-specific parameters
        $bankAccounts = $agency->bankAccounts()->limit(5)->get();
        $policies = $agency->policies()->limit(5)->get();
        $taxRegulations = $agency->taxRegulations()->limit(5)->get();
        
        // Get recent activities for this agency
        $recentActivities = $agency->auditTrails()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get alerts for this agency
        $alerts = $this->getAgencyAlerts($agency);
        
        return view('companies.dashboard.agency', compact(
            'agency',
            'cashRegisters',
            'totalBalance',
            'encaissements',
            'decaissements',
            'netCashFlow',
            'transactionCount',
            'averageTransaction',
            'recentTransactions',
            'bankAccounts',
            'policies',
            'taxRegulations',
            'recentActivities',
            'alerts'
        ));
    }
    
    /**
     * Get global alerts for the dashboard
     */
    private function getGlobalAlerts()
    {
        $alerts = [];
        
        // Check for inactive companies
        $inactiveCompanies = Company::where('active', false)->count();
        if ($inactiveCompanies > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$inactiveCompanies} société(s) inactive(s) dans le système",
                'icon' => 'fas fa-building'
            ];
        }
        
        // Check for agencies in standby
        $standbyAgencies = Agency::inStandby()->count();
        if ($standbyAgencies > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "{$standbyAgencies} agence(s) en veille",
                'icon' => 'fas fa-store'
            ];
        }
        
        // Check for recent high-risk activities
        $highRiskActivities = EntityAuditTrail::where('created_at', '>=', now()->subDay())
            ->where('action', 'deleted')
            ->count();
        if ($highRiskActivities > 0) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "{$highRiskActivities} suppression(s) récente(s) détectée(s)",
                'icon' => 'fas fa-exclamation-triangle'
            ];
        }
        
        return $alerts;
    }
    
    /**
     * Get company-specific alerts
     */
    private function getCompanyAlerts($company)
    {
        $alerts = [];
        
        // Check for agencies in standby for this company
        $standbyAgencies = $company->agencies()->inStandby()->count();
        if ($standbyAgencies > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "{$standbyAgencies} agence(s) en veille",
                'icon' => 'fas fa-store'
            ];
        }
        
        // Check for recent activities
        $recentActivities = $company->auditTrails()
            ->where('created_at', '>=', now()->subWeek())
            ->count();
        if ($recentActivities == 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Aucune activité détectée cette semaine",
                'icon' => 'fas fa-clock'
            ];
        }
        
        return $alerts;
    }
    
    /**
     * Get agency-specific alerts
     */
    private function getAgencyAlerts($agency)
    {
        $alerts = [];
        
        // Check agency status
        if ($agency->statut !== 'active') {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Cette agence est actuellement {$agency->statut}",
                'icon' => 'fas fa-store'
            ];
        }
        
        // Check for recent activities
        $recentActivities = $agency->auditTrails()
            ->where('created_at', '>=', now()->subWeek())
            ->count();
        if ($recentActivities == 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Aucune activité détectée cette semaine",
                'icon' => 'fas fa-clock'
            ];
        }
        
        return $alerts;
    }
}
