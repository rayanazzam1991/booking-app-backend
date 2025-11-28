<?php

namespace Database\Factories;

use App\Enums\HealthProfessionalStatus;
use App\Models\HealthProfessional;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthProfessionalService>
 */
class HealthProfessionalServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'health_professional_id' => HealthProfessional::factory(),
            'service_id' => Service::factory(),
            'price' => $this->faker->randomFloat(2, 50, 500),
            'duration_minutes' => $this->faker->numberBetween(15, 120),
            'notes' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(collect(HealthProfessionalStatus::cases())->pluck('value')->toArray()),
        ];
    }
}
