<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use App\Models\SupplierOrder;
use App\Models\SupplierDelivery;
use App\Models\SupplierPayment;
use App\Models\SupplierInvoice;
use App\Models\SupplierIssue;
use App\Models\SupplierContract;
use Illuminate\Support\Facades\DB;

class SupplierDashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $totalSuppliers = Fournisseur::count();
        $activeSuppliers = Fournisseur::where('statut', 'actif')->count();
        
        // Commandes
        $totalOrders = SupplierOrder::count();
        $pendingOrders = SupplierOrder::where('statut', 'pending')->count();
        $deliveredOrders = SupplierOrder::where('statut', 'delivered')->count();
        
        // Livraisons
        $totalDeliveries = SupplierDelivery::count();
        $partialDeliveries = SupplierDelivery::where('statut', 'partial')->count();
        
        // Paiements
        $totalPayments = SupplierPayment::sum('montant');
        $paymentsThisMonth = SupplierPayment::whereMonth('date_paiement', now()->month)
            ->whereYear('date_paiement', now()->year)
            ->sum('montant');
        
        // Factures
        $totalInvoices = SupplierInvoice::count();
        $overdueInvoices = SupplierInvoice::where('date_echeance', '<', now())
            ->whereRaw('montant_total > montant_paye')
            ->count();
        $overdueAmount = SupplierInvoice::where('date_echeance', '<', now())
            ->whereRaw('montant_total > montant_paye')
            ->sum(DB::raw('montant_total - montant_paye'));
        
        // Réclamations
        $totalIssues = SupplierIssue::count();
        $openIssues = SupplierIssue::where('statut', 'open')->count();
        
        // Top fournisseurs par volume d'achats
        $topSuppliers = SupplierOrder::with('fournisseur')
            ->select('fournisseur_id', DB::raw('SUM(montant_total) as total_amount'), DB::raw('COUNT(*) as order_count'))
            ->groupBy('fournisseur_id')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get();
        
        // Évolution des dépenses par mois (6 derniers mois)
        $monthlyExpenses = SupplierPayment::select(
                DB::raw('DATE_FORMAT(date_paiement, "%Y-%m") as month'),
                DB::raw('SUM(montant) as total')
            )
            ->where('date_paiement', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Fournisseurs à risque (retards/anomalies)
        $riskySuppliers = Fournisseur::whereHas('supplierOrders', function($query) {
                $query->where('statut', 'pending')
                    ->where('date_livraison_prevue', '<', now());
            })
            ->orWhereHas('supplierIssues', function($query) {
                $query->where('statut', 'open');
            })
            ->withCount(['supplierOrders', 'supplierIssues'])
            ->get();
        
        // Fournisseurs les mieux notés
        $topRatedSuppliers = Fournisseur::with('supplierRatings')
            ->has('supplierRatings')
            ->withAvg('supplierRatings', 'overall_score')
            ->orderBy('supplier_ratings_avg_overall_score', 'desc')
            ->limit(5)
            ->get();
        
        // Contrats expirant bientôt
        $expiringContracts = SupplierContract::with('fournisseur')
            ->where('status', 'active')
            ->where('end_date', '<=', now()->addDays(30))
            ->where('end_date', '>=', now())
            ->orderBy('end_date', 'asc')
            ->get();
        
        // Répartition par type d'activité
        $suppliersByActivity = Fournisseur::select('activite', DB::raw('count(*) as count'))
            ->groupBy('activite')
            ->get();
        
        return view('fournisseurs.dashboard', compact(
            'totalSuppliers', 'activeSuppliers',
            'totalOrders', 'pendingOrders', 'deliveredOrders',
            'totalDeliveries', 'partialDeliveries',
            'totalPayments', 'paymentsThisMonth',
            'totalInvoices', 'overdueInvoices', 'overdueAmount',
            'totalIssues', 'openIssues',
            'topSuppliers', 'monthlyExpenses', 'riskySuppliers',
            'topRatedSuppliers', 'expiringContracts',
            'suppliersByActivity'
        ));
    }
}
