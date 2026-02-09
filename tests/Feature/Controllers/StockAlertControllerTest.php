<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\StockAlert;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StockAlertControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected Product $product;
    protected Warehouse $warehouse;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
        $this->warehouse = Warehouse::factory()->create();
    }

    /** @test */
    public function user_can_view_alerts_list()
    {
        $alert = StockAlert::factory()->create([
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('stock.alerts.index'));

        $response->assertStatus(200)
            ->assertViewIs('stock.alerts.index')
            ->assertViewHas('alerts')
            ->assertSee($this->product->name)
            ->assertSee($this->warehouse->name);
    }

    /** @test */
    public function user_can_create_alert()
    {
        $alertData = [
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'minimum_threshold' => 10,
            'security_threshold' => 20,
            'is_active' => true,
            'email_notifications' => true
        ];

        $response = $this->actingAs($this->user)
            ->post(route('stock.alerts.store'), $alertData);

        $response->assertRedirect(route('stock.alerts.index'));
        $this->assertDatabaseHas('stock_alerts', $alertData);
    }

    /** @test */
    public function user_cannot_create_alert_with_invalid_data()
    {
        $response = $this->actingAs($this->user)
            ->post(route('stock.alerts.store'), [
                'product_id' => 999, // ID inexistant
                'warehouse_id' => $this->warehouse->id,
                'minimum_threshold' => 'invalid',
                'security_threshold' => 5
            ]);

        $response->assertSessionHasErrors(['product_id', 'minimum_threshold']);
    }

    /** @test */
    public function user_can_update_alert()
    {
        $alert = StockAlert::factory()->create([
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id
        ]);

        $updatedData = [
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'minimum_threshold' => 15,
            'security_threshold' => 25,
            'is_active' => false,
            'email_notifications' => false
        ];

        $response = $this->actingAs($this->user)
            ->put(route('stock.alerts.update', $alert), $updatedData);

        $response->assertRedirect(route('stock.alerts.index'));
        $this->assertDatabaseHas('stock_alerts', $updatedData);
    }

    /** @test */
    public function user_can_delete_alert()
    {
        $alert = StockAlert::factory()->create([
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('stock.alerts.destroy', $alert));

        $response->assertRedirect(route('stock.alerts.index'));
        $this->assertDatabaseMissing('stock_alerts', ['id' => $alert->id]);
    }

    /** @test */
    public function user_can_toggle_alert_status()
    {
        $alert = StockAlert::factory()->create([
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'is_active' => true
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('stock.alerts.toggle-status', $alert));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('stock_alerts', [
            'id' => $alert->id,
            'is_active' => false
        ]);
    }

    /** @test */
    public function user_can_toggle_notifications()
    {
        $alert = StockAlert::factory()->create([
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'email_notifications' => true
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('stock.alerts.toggle-notifications', $alert));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('stock_alerts', [
            'id' => $alert->id,
            'email_notifications' => false
        ]);
    }
}
