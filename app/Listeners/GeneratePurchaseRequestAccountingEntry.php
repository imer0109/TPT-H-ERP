<?php

namespace App\Listeners;

use App\Events\PurchaseRequestValidated;
use App\Services\AccountingIntegrationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GeneratePurchaseRequestAccountingEntry
{
    /**
     * The accounting integration service.
     */
    protected $accountingService;

    /**
     * Create the event listener.
     */
    public function __construct(AccountingIntegrationService $accountingService)
    {
        $this->accountingService = $accountingService;
    }

    /**
     * Handle the event.
     */
    public function handle(PurchaseRequestValidated $event): void
    {
        $this->accountingService->generatePurchaseRequestEntries($event->purchaseRequest);
    }
}