<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\StockTransfer;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StockTransferTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_create_stock_transfer()
    {
        $sourceWarehouse = Warehouse::factory()->create();
        $destWarehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->post(route('stock.transfers.store'), [
            'warehouse_source_id' => $sourceWarehouse->id,
            'warehouse_destination_id' => $destWarehouse->id,
            'product_id' => $product->id,
            'quantite' => 10,
            'unite' => 'pcs',
            'notes' => 'Test transfer'
        ]);

        $response->assertRedirect(route('stock.transfers.index'));
        $this->assertDatabaseHas('stock_transfers', [
            'warehouse_source_id' => $sourceWarehouse->id,
            'warehouse_destination_id' => $destWarehouse->id,
            'product_id' => $product->id,
            'quantite' => 10,
            'statut' => 'en_attente'
        ]);
    }

    public function test_user_can_validate_stock_transfer()
    {
        $transfer = StockTransfer::factory()->create([
            'statut' => 'en_attente'
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('stock.transfers.validate', $transfer));

        $response->assertRedirect();
        $this->assertDatabaseHas('stock_transfers', [
            'id' => $transfer->id,
            'statut' => 'en_transit',
            'validated_by' => $this->user->id
        ]);
    }

    public function test_user_can_receive_stock_transfer()
    {
        $transfer = StockTransfer::factory()->create([
            'statut' => 'en_transit'
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('stock.transfers.receive', $transfer));

        $response->assertRedirect();
        $this->assertDatabaseHas('stock_transfers', [
            'id' => $transfer->id,
            'statut' => 'receptionne',
            'received_by' => $this->user->id
        ]);
    }

    public function test_user_cannot_validate_already_validated_transfer()
    {
        $transfer = StockTransfer::factory()->create([
            'statut' => 'en_transit'
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('stock.transfers.validate', $transfer));

        $response->assertRedirect();
        $this->assertDatabaseHas('stock_transfers', [
            'id' => $transfer->id,
            'statut' => 'en_transit'
        ]);
    }

    public function test_user_cannot_receive_unvalidated_transfer()
    {
        $transfer = StockTransfer::factory()->create([
            'statut' => 'en_attente'
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('stock.transfers.receive', $transfer));

        $response->assertRedirect();
        $this->assertDatabaseHas('stock_transfers', [
            'id' => $transfer->id,
            'statut' => 'en_attente'
        ]);
    }
}