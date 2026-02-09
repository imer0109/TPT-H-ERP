<?php

namespace Database\Factories;

use App\Models\TypeProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeProductFactory extends Factory
{
    protected $model = TypeProduct::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}