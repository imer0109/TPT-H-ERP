<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 100, 10000),
            'cost' => $this->faker->randomFloat(2, 50, 5000),
            'category_id' => \App\Models\Category::factory(),
            'type_product_id' => \App\Models\TypeProduct::factory(),
            'unite' => $this->faker->randomElement(['pcs', 'kg', 'liters', 'meters']),
            'quantite' => $this->faker->numberBetween(0, 1000),
            'prix_achat' => $this->faker->randomFloat(2, 50, 5000),
            'prix_vente' => $this->faker->randomFloat(2, 100, 10000),
            'seuil_alerte' => $this->faker->numberBetween(10, 100),
        ];
    }
}
