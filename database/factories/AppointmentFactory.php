<?php

namespace Database\Factories;

use App\Models\HealthProfessional;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $scheduledAt = Carbon::now()->addDays(2)->setTime(10, 0);

        return [
            'service_id' => Service::factory(),
            'health_professional_id' => HealthProfessional::factory(),
            'scheduled_at' => $scheduledAt,
            'customer_email' => fake()->safeEmail(),
        ];
    }
}
