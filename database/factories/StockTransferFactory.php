<?php

namespace Database\Factories;

use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockTransferFactory extends Factory
{
    protected $model = StockTransfer::class;

    public function definition()
    {
        return [
            'from_warehouse_id' => Warehouse::factory(),
            'to_warehouse_id' => Warehouse::factory(),
            'created_by' => User::factory(),
            'validated_by' => User::factory(),
            'received_by' => User::factory(),
            'status' => $this->faker->randomElement(['pending', 'validated', 'received', 'cancelled']),
            'notes' => $this->faker->sentence,
        ];
    }
}
