<?php

namespace Database\Seeders;

use App\Models\HealthProfessional;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HealthProfessionalServiceSeeder extends Seeder
{
    public function run(): void
    {
        // ------------------------------------------------------------
        // 1. Create sample services
        // ------------------------------------------------------------
        $servicesData = [
            ['name' => 'General Consultation', 'duration_minutes' => 30, 'price' => 50],
            ['name' => 'Physiotherapy Session', 'duration_minutes' => 60, 'price' => 90],
            ['name' => 'Dental Cleaning', 'duration_minutes' => 45, 'price' => 120],
            ['name' => 'Eye Examination', 'duration_minutes' => 40, 'price' => 85],
        ];

        $services = [];

        foreach ($servicesData as $srv) {
            $services[] = Service::firstOrCreate(
                ['name' => $srv['name']],
                [
                    'description' => $srv['name'].' service',
                    'duration_minutes' => $srv['duration_minutes'],
                    'price' => $srv['price'],
                ]
            );
        }

        // ------------------------------------------------------------
        // 2. Create health professionals (users + profile)
        // ------------------------------------------------------------
        $professionalsData = [
            [
                'name' => 'Dr. Laura Kim',
                'email' => 'laura@example.com',
                'speciality' => 'General Practitioner',
            ],
            [
                'name' => 'Dr. Samuel Rivera',
                'email' => 'samuel@example.com',
                'speciality' => 'Physiotherapist',
            ],
            [
                'name' => 'Dr. Nina Dimitri',
                'email' => 'nina@example.com',
                'speciality' => 'Dentist',
            ],
        ];

        $professionals = [];

        foreach ($professionalsData as $prof) {
            // 2.1 Create the user
            $user = User::firstOrCreate(
                ['email' => $prof['email']],
                [
                    'name' => $prof['name'],
                    'password' => Hash::make('password'),
                ]
            );

            // 2.2 Create the health professional profile
            $professionals[] = HealthProfessional::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'speciality' => $prof['speciality'],
                    'license_number' => strtoupper(fake()->bothify('LIC-####')),
                ]
            );
        }

        // ------------------------------------------------------------
        // 3. Attach services to professionals with pivot overrides
        // ------------------------------------------------------------
        foreach ($professionals as $professional) {

            // Randomly attach between 1–3 services
            $randomServices = collect($services)->random(rand(1, 3));

            foreach ($randomServices as $service) {

                $professional->services()->attach($service->id, [
                    'price' => $service->price + rand(5, 50), // override price
                    'duration_minutes' => $service->duration_minutes ?? null,
                    'notes' => 'Available for this service.',
                ]);
            }
        }
    }
}
