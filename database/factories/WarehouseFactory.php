<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->company . ' Warehouse',
            'adresse' => $this->faker->address,
            'ville' => $this->faker->city,
            'pays' => $this->faker->country,
            'code_postal' => $this->faker->postcode,
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'responsable' => $this->faker->name,
        ];
    }
}
