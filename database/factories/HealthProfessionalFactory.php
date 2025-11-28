<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthProfessional>
 */
class HealthProfessionalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'speciality' => fake()->randomElement(['Cardiology', 'Dermatology', 'Physiotherapy']),
            'license_number' => fake()->bothify('LIC-####'),
        ];
    }
}
