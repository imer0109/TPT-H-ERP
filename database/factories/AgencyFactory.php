<?php

namespace Database\Factories;

use App\Models\Agency;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgencyFactory extends Factory
{
    protected $model = Agency::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->company . ' Agency',
            'code_unique' => $this->faker->unique()->bothify('AGY-####'),
            'adresse' => $this->faker->address,
            'responsable_id' => User::factory(),
            'zone_geographique' => $this->faker->city,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'company_id' => Company::factory(),
            'statut' => $this->faker->randomElement(['active', 'en veille'])
        ];
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'statut' => 'active',
            ];
        });
    }

    public function standby()
    {
        return $this->state(function (array $attributes) {
            return [
                'statut' => 'en veille',
            ];
        });
    }
}