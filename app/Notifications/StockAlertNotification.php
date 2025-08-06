<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StockAlertNotification extends Notification
{
    use Queueable;

    protected $product;
    protected $warehouse;
    protected $alertType;
    protected $currentStock;
    protected $threshold;

    public function __construct(Product $product, Warehouse $warehouse, string $alertType, float $currentStock, float $threshold)
    {
        $this->product = $product;
        $this->warehouse = $warehouse;
        $this->alertType = $alertType;
        $this->currentStock = $currentStock;
        $this->threshold = $threshold;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->alertType === 'minimum' 
            ? 'ALERTE STOCK CRITIQUE' 
            : 'Alerte Seuil de Sécurité';

        return (new MailMessage)
            ->subject($subject . ' - ' . $this->product->nom)
            ->line('Une alerte de stock a été déclenchée pour :')
            ->line('Produit : ' . $this->product->nom)
            ->line('Dépôt : ' . $this->warehouse->nom)
            ->line('Stock actuel : ' . $this->currentStock . ' ' . $this->product->unite)
            ->line('Seuil ' . ($this->alertType === 'minimum' ? 'minimum' : 'de sécurité') . ' : ' . $this->threshold . ' ' . $this->product->unite)
            ->action('Voir le produit', route('stock.products.show', $this->product->id));
    }

    public function toArray($notifiable)
    {
        return [
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'alert_type' => $this->alertType,
            'current_stock' => $this->currentStock,
            'threshold' => $this->threshold
        ];
    }
}