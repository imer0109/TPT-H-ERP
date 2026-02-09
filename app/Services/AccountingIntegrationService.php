<?php

namespace App\Services;

use App\Models\AccountingEntry;
use App\Models\AccountingJournal;
use App\Models\ChartOfAccount;
use App\Models\PurchaseRequest;
use App\Models\SupplierOrder;
use App\Models\Supplier;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountingIntegrationService
{
    /**
     * Generate accounting entries for purchase request validation
     */
    public function generatePurchaseRequestEntries(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'validée') {
            return false;
        }

        try {
            DB::beginTransaction();

            // Get or create purchase journal
            $journal = AccountingJournal::firstOrCreate([
                'code' => 'ACH',
                'company_id' => $purchaseRequest->company_id
            ], [
                'name' => 'Journal des Achats',
                'type' => 'achat'
            ]);

            // Get accounts
            $expenseAccount = ChartOfAccount::where('code', '6011')->first(); // Achat de marchandises
            $supplierAccount = ChartOfAccount::where('code', '4011')->first(); // Fournisseurs

            if (!$expenseAccount || !$supplierAccount) {
                throw new \Exception('Comptes comptables requis non trouvés');
            }

            $pieceNumber = $this->generatePieceNumber($journal, now());
            $totalAmount = $purchaseRequest->total_amount;

            // Debit expense account
            AccountingEntry::create([
                'company_id' => $purchaseRequest->company_id,
                'journal_id' => $journal->id,
                'account_id' => $expenseAccount->id,
                'date' => now(),
                'piece_number' => $pieceNumber,
                'description' => 'Demande d\'achat DA-' . $purchaseRequest->request_number,
                'reference' => 'DA-' . $purchaseRequest->request_number,
                'debit' => $totalAmount,
                'credit' => 0,
                'status' => 'validée',
                'created_by' => auth()->id()
            ]);

            // Credit supplier account
            AccountingEntry::create([
                'company_id' => $purchaseRequest->company_id,
                'journal_id' => $journal->id,
                'account_id' => $supplierAccount->id,
                'date' => now(),
                'piece_number' => $pieceNumber,
                'description' => 'Demande d\'achat DA-' . $purchaseRequest->request_number,
                'reference' => 'DA-' . $purchaseRequest->request_number,
                'debit' => 0,
                'credit' => $totalAmount,
                'status' => 'validée',
                'created_by' => auth()->id()
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur génération écriture DA: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate accounting entries for supplier order
     */
    public function generateSupplierOrderEntries(SupplierOrder $supplierOrder)
    {
        if ($supplierOrder->status !== 'validée') {
            return false;
        }

        try {
            DB::beginTransaction();

            // Get or create purchase journal
            $journal = AccountingJournal::firstOrCreate([
                'code' => 'ACH',
                'company_id' => $supplierOrder->company_id
            ], [
                'name' => 'Journal des Achats',
                'type' => 'achat'
            ]);

            // Get supplier account or create it
            $supplierAccount = $this->getOrCreateSupplierAccount($supplierOrder->supplier);
            $expenseAccount = ChartOfAccount::where('code', '6011')->first(); // Achat de marchandises

            if (!$expenseAccount || !$supplierAccount) {
                throw new \Exception('Comptes comptables requis non trouvés');
            }

            $pieceNumber = $this->generatePieceNumber($journal, $supplierOrder->order_date);
            $totalAmount = $supplierOrder->total_amount;

            // Debit expense account
            AccountingEntry::create([
                'company_id' => $supplierOrder->company_id,
                'journal_id' => $journal->id,
                'account_id' => $expenseAccount->id,
                'date' => $supplierOrder->order_date,
                'piece_number' => $pieceNumber,
                'description' => 'Commande fournisseur BOC-' . $supplierOrder->order_number,
                'reference' => 'BOC-' . $supplierOrder->order_number,
                'debit' => $totalAmount,
                'credit' => 0,
                'status' => 'validée',
                'created_by' => auth()->id()
            ]);

            // Credit supplier account
            AccountingEntry::create([
                'company_id' => $supplierOrder->company_id,
                'journal_id' => $journal->id,
                'account_id' => $supplierAccount->id,
                'date' => $supplierOrder->order_date,
                'piece_number' => $pieceNumber,
                'description' => 'Commande fournisseur BOC-' . $supplierOrder->order_number,
                'reference' => 'BOC-' . $supplierOrder->order_number,
                'debit' => 0,
                'credit' => $totalAmount,
                'status' => 'validée',
                'created_by' => auth()->id()
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur génération écriture BOC: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate accounting entries for cash movements
     */
    public function generateCashMovementEntries($cashMovement)
    {
        try {
            DB::beginTransaction();

            // Get or create cash journal
            $journal = AccountingJournal::firstOrCreate([
                'code' => 'CAI',
                'company_id' => $cashMovement->company_id
            ], [
                'name' => 'Journal de Caisse',
                'type' => 'caisse'
            ]);

            // Get cash account
            $cashAccount = ChartOfAccount::where('code', '5711')->first(); // Caisse
            $thirdPartyAccount = $this->getThirdPartyAccount($cashMovement);

            if (!$cashAccount || !$thirdPartyAccount) {
                throw new \Exception('Comptes comptables requis non trouvés');
            }

            $pieceNumber = $this->generatePieceNumber($journal, $cashMovement->date);

            if ($cashMovement->type === 'recette') {
                // Cash receipt: Debit Cash, Credit Third Party
                AccountingEntry::create([
                    'company_id' => $cashMovement->company_id,
                    'journal_id' => $journal->id,
                    'account_id' => $cashAccount->id,
                    'date' => $cashMovement->date,
                    'piece_number' => $pieceNumber,
                    'description' => $cashMovement->description,
                    'reference' => $cashMovement->reference,
                    'debit' => $cashMovement->amount,
                    'credit' => 0,
                    'status' => 'validée',
                    'created_by' => auth()->id()
                ]);

                AccountingEntry::create([
                    'company_id' => $cashMovement->company_id,
                    'journal_id' => $journal->id,
                    'account_id' => $thirdPartyAccount->id,
                    'date' => $cashMovement->date,
                    'piece_number' => $pieceNumber,
                    'description' => $cashMovement->description,
                    'reference' => $cashMovement->reference,
                    'debit' => 0,
                    'credit' => $cashMovement->amount,
                    'status' => 'validée',
                    'created_by' => auth()->id()
                ]);
            } else {
                // Cash disbursement: Debit Third Party, Credit Cash
                AccountingEntry::create([
                    'company_id' => $cashMovement->company_id,
                    'journal_id' => $journal->id,
                    'account_id' => $thirdPartyAccount->id,
                    'date' => $cashMovement->date,
                    'piece_number' => $pieceNumber,
                    'description' => $cashMovement->description,
                    'reference' => $cashMovement->reference,
                    'debit' => $cashMovement->amount,
                    'credit' => 0,
                    'status' => 'validée',
                    'created_by' => auth()->id()
                ]);

                AccountingEntry::create([
                    'company_id' => $cashMovement->company_id,
                    'journal_id' => $journal->id,
                    'account_id' => $cashAccount->id,
                    'date' => $cashMovement->date,
                    'piece_number' => $pieceNumber,
                    'description' => $cashMovement->description,
                    'reference' => $cashMovement->reference,
                    'debit' => 0,
                    'credit' => $cashMovement->amount,
                    'status' => 'validée',
                    'created_by' => auth()->id()
                ]);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur génération écriture caisse: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate accounting entries for sales
     */
    public function generateSalesEntries($sale)
    {
        try {
            DB::beginTransaction();

            // Get or create sales journal
            $journal = AccountingJournal::firstOrCreate([
                'code' => 'VTE',
                'company_id' => $sale->company_id
            ], [
                'name' => 'Journal des Ventes',
                'type' => 'vente'
            ]);

            // Get accounts
            $customerAccount = $this->getOrCreateCustomerAccount($sale->customer);
            $revenueAccount = ChartOfAccount::where('code', '7011')->first(); // Ventes de marchandises

            if (!$customerAccount || !$revenueAccount) {
                throw new \Exception('Comptes comptables requis non trouvés');
            }

            $pieceNumber = $this->generatePieceNumber($journal, $sale->sale_date);

            // Debit customer account
            AccountingEntry::create([
                'company_id' => $sale->company_id,
                'journal_id' => $journal->id,
                'account_id' => $customerAccount->id,
                'date' => $sale->sale_date,
                'piece_number' => $pieceNumber,
                'description' => 'Vente ' . $sale->sale_number,
                'reference' => $sale->sale_number,
                'debit' => $sale->total_amount,
                'credit' => 0,
                'status' => 'validée',
                'created_by' => auth()->id()
            ]);

            // Credit revenue account
            AccountingEntry::create([
                'company_id' => $sale->company_id,
                'journal_id' => $journal->id,
                'account_id' => $revenueAccount->id,
                'date' => $sale->sale_date,
                'piece_number' => $pieceNumber,
                'description' => 'Vente ' . $sale->sale_number,
                'reference' => $sale->sale_number,
                'debit' => 0,
                'credit' => $sale->total_amount,
                'status' => 'validée',
                'created_by' => auth()->id()
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur génération écriture vente: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get or create supplier account
     */
    private function getOrCreateSupplierAccount(Supplier $supplier)
    {
        $accountCode = '4011' . str_pad($supplier->id, 3, '0', STR_PAD_LEFT);
        
        return ChartOfAccount::firstOrCreate([
            'code' => $accountCode,
            'company_id' => $supplier->company_id
        ], [
            'name' => 'Fournisseur - ' . $supplier->name,
            'type' => 'passif',
            'level' => 'sous_compte',
            'parent_id' => ChartOfAccount::where('code', '4011')->first()?->id,
            'is_active' => true
        ]);
    }

    /**
     * Get or create customer account
     */
    private function getOrCreateCustomerAccount($customer)
    {
        $accountCode = '4111' . str_pad($customer->id, 3, '0', STR_PAD_LEFT);
        
        return ChartOfAccount::firstOrCreate([
            'code' => $accountCode,
            'company_id' => $customer->company_id
        ], [
            'name' => 'Client - ' . $customer->name,
            'type' => 'actif',
            'level' => 'sous_compte',
            'parent_id' => ChartOfAccount::where('code', '4111')->first()?->id,
            'is_active' => true
        ]);
    }

    /**
     * Get third party account for cash movements
     */
    private function getThirdPartyAccount($cashMovement)
    {
        // This would depend on the cash movement type and related entity
        // For now, return a generic account
        return ChartOfAccount::where('code', '4621')->first(); // Créditeurs divers
    }

    /**
     * Generate piece number for journal entries
     */
    private function generatePieceNumber(AccountingJournal $journal, Carbon $date)
    {
        $year = $date->year;
        $month = $date->month;
        
        $lastEntry = AccountingEntry::where('journal_id', $journal->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('piece_number', 'desc')
            ->first();

        if ($lastEntry && preg_match('/(\d+)$/', $lastEntry->piece_number, $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }

        return $journal->code . $year . str_pad($month, 2, '0', STR_PAD_LEFT) . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}