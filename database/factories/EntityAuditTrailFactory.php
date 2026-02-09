<?php

namespace Database\Factories;

use App\Models\EntityAuditTrail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntityAuditTrailFactory extends Factory
{
    protected $model = EntityAuditTrail::class;

    public function definition()
    {
        return [
            'entity_id' => 1,
            'entity_type' => $this->faker->randomElement(['company', 'agency']),
            'user_id' => User::factory(),
            'action' => $this->faker->randomElement(['created', 'updated', 'deleted', 'archived', 'duplicated']),
            'changes' => null,
            'description' => $this->faker->sentence,
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
        ];
    }

    public function forCompany($companyId = null)
    {
        return $this->state(function (array $attributes) use ($companyId) {
            return [
                'entity_id' => $companyId ?? 1,
                'entity_type' => 'company',
            ];
        });
    }

    public function forAgency($agencyId = null)
    {
        return $this->state(function (array $attributes) use ($agencyId) {
            return [
                'entity_id' => $agencyId ?? 1,
                'entity_type' => 'agency',
            ];
        });
    }
}