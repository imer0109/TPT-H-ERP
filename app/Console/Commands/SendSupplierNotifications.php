<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SupplierContract;
use App\Models\SupplierInvoice;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendSupplierNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suppliers:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automatic notifications for contract renewals and overdue payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending supplier notifications...');
        
        // Send contract expiration notifications
        $this->sendContractExpirationNotifications();
        
        // Send overdue payment notifications
        $this->sendOverduePaymentNotifications();
        
        $this->info('Supplier notifications sent successfully.');
    }

    /**
     * Send notifications for contracts expiring soon
     */
    private function sendContractExpirationNotifications()
    {
        // Get contracts expiring within 30 days
        $expiringContracts = SupplierContract::where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays(30)])
            ->with('fournisseur', 'responsible')
            ->get();

        foreach ($expiringContracts as $contract) {
            $this->sendContractNotification($contract);
        }

        $this->info("Sent {$expiringContracts->count()} contract expiration notifications.");
    }

    /**
     * Send notification for a specific contract
     */
    private function sendContractNotification($contract)
    {
        try {
            // Get responsible user (contract manager)
            $responsible = $contract->responsible;
            
            if ($responsible && $responsible->email) {
                // Send email notification
                Mail::raw(
                    "Le contrat {$contract->contract_number} pour le fournisseur {$contract->fournisseur->raison_sociale} " .
                    "expire le " . $contract->end_date->format('d/m/Y') . ".\n\n" .
                    "Veuillez prendre les mesures nécessaires pour renouveler ou résilier ce contrat.",
                    function ($message) use ($responsible, $contract) {
                        $message->to($responsible->email)
                            ->subject("Contrat à renouveler - {$contract->contract_number}");
                    }
                );
                
                Log::info("Contract expiration notification sent to {$responsible->email} for contract {$contract->contract_number}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send contract notification: " . $e->getMessage());
        }
    }

    /**
     * Send notifications for overdue payments
     */
    private function sendOverduePaymentNotifications()
    {
        // Get overdue invoices (past due date and not fully paid)
        $overdueInvoices = SupplierInvoice::where('date_echeance', '<', now())
            ->whereColumn('montant_paye', '<', 'montant_total')
            ->with('fournisseur')
            ->get();

        foreach ($overdueInvoices as $invoice) {
            $this->sendOverduePaymentNotification($invoice);
        }

        $this->info("Sent {$overdueInvoices->count()} overdue payment notifications.");
    }

    /**
     * Send notification for a specific overdue invoice
     */
    private function sendOverduePaymentNotification($invoice)
    {
        try {
            // Get finance team users (you might want to customize this)
            $financeUsers = User::whereHas('roles', function($query) {
                $query->where('name', 'responsable_financier');
            })->get();

            foreach ($financeUsers as $user) {
                if ($user->email) {
                    // Send email notification
                    Mail::raw(
                        "La facture {$invoice->numero_facture} du fournisseur {$invoice->fournisseur->raison_sociale} " .
                        "est en retard de paiement depuis le " . $invoice->date_echeance->format('d/m/Y') . ".\n\n" .
                        "Montant restant dû: " . number_format($invoice->solde, 2) . " " . $invoice->devise,
                        function ($message) use ($user, $invoice) {
                            $message->to($user->email)
                                ->subject("Paiement en retard - Facture {$invoice->numero_facture}");
                        }
                    );
                    
                    Log::info("Overdue payment notification sent to {$user->email} for invoice {$invoice->numero_facture}");
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to send overdue payment notification: " . $e->getMessage());
        }
    }
}