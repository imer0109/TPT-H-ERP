<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\StockAlert;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\StockAlertService;
use App\Notifications\StockAlertNotification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;

class StockAlertServiceTest extends TestCase
{
    use WithFaker;

    protected StockAlertService $service;
    protected Product $product;
    protected Warehouse $warehouse;
    protected StockAlert $alert;

    protected function setUp(): void
    {
        parent::setUp();
        
        Notification::fake();
        
        $this->service = new StockAlertService();
        
        $this->product = Product::factory()->create([
            'name' => 'Test Product',
            'unite' => 'unités'
        ]);
        
        $this->warehouse = Warehouse::factory()->create([
            'name' => 'Test Warehouse'
        ]);
        
        $this->alert = StockAlert::factory()->create([
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'minimum_threshold' => 10,
            'security_threshold' => 20,
            'is_active' => true,
            'email_notifications' => true
        ]);
    }

    /** @test */
    public function it_sends_notification_when_stock_below_minimum_threshold()
    {
        // Simuler un niveau de stock en dessous du seuil minimum
        $this->product->stocks()->create([
            'warehouse_id' => $this->warehouse->id,
            'quantity' => 5
        ]);

        $this->service->checkStockLevels();

        Notification::assertSentTo(
            $this->alert->warehouse->users,
            StockAlertNotification::class,
            function ($notification) {
                return $notification->alert->id === $this->alert->id
                    && $notification->level === 'critical';
            }
        );
    }

    /** @test */
    public function it_sends_notification_when_stock_below_security_threshold()
    {
        // Simuler un niveau de stock entre les seuils minimum et de sécurité
        $this->product->stocks()->create([
            'warehouse_id' => $this->warehouse->id,
            'quantity' => 15
        ]);

        $this->service->checkStockLevels();

        Notification::assertSentTo(
            $this->alert->warehouse->users,
            StockAlertNotification::class,
            function ($notification) {
                return $notification->alert->id === $this->alert->id
                    && $notification->level === 'warning';
            }
        );
    }

    /** @test */
    public function it_does_not_send_notification_when_stock_above_thresholds()
    {
        // Simuler un niveau de stock au-dessus des seuils
        $this->product->stocks()->create([
            'warehouse_id' => $this->warehouse->id,
            'quantity' => 25
        ]);

        $this->service->checkStockLevels();

        Notification::assertNothingSent();
    }

    /** @test */
    public function it_does_not_send_notification_when_alert_is_inactive()
    {
        $this->alert->update(['is_active' => false]);

        $this->product->stocks()->create([
            'warehouse_id' => $this->warehouse->id,
            'quantity' => 5
        ]);

        $this->service->checkStockLevels();

        Notification::assertNothingSent();
    }

    /** @test */
    public function it_does_not_send_email_when_email_notifications_disabled()
    {
        $this->alert->update(['email_notifications' => false]);

        $this->product->stocks()->create([
            'warehouse_id' => $this->warehouse->id,
            'quantity' => 5
        ]);

        $this->service->checkStockLevels();

        Notification::assertNothingSentTo($this->alert->warehouse->users);
    }
}