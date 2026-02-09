<?php

namespace App\Services;

use App\Models\StockAlert;
use App\Models\Product;
use App\Models\Warehouse;
use App\Notifications\StockAlertNotification;
use Illuminate\Support\Facades\Notification;

class StockAlertService
{
    public function checkStockLevels()
    {
        $alerts = StockAlert::where('alerte_active', true)->get();

        foreach ($alerts as $alert) {
            $stockLevel = $alert->product->getStockInWarehouse($alert->warehouse_id);

            if ($stockLevel <= $alert->seuil_minimum) {
                $this->sendStockAlert($alert, 'minimum', $stockLevel);
            } elseif ($stockLevel <= $alert->seuil_securite) {
                $this->sendStockAlert($alert, 'securite', $stockLevel);
            }
        }
    }

    protected function sendStockAlert(StockAlert $alert, string $type, float $currentStock)
    {
        $notification = new StockAlertNotification(
            $alert->product,
            $alert->warehouse,
            $type,
            $currentStock,
            $type === 'minimum' ? $alert->seuil_minimum : $alert->seuil_securite
        );

        // Notifier les utilisateurs configurés
        if ($alert->email_notifications) {
            $emails = explode(',', $alert->email_notifications);
            Notification::route('mail', $emails)->notify($notification);
        }

        // Notifier le créateur de l'alerte
        $alert->createdBy->notify($notification);
    }
}
