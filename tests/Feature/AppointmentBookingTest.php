<?php

namespace Tests\Feature;

use App\Jobs\SendAppointmentConfirmationEmailJob;
use App\Models\Appointment;
use App\Models\HealthProfessional;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class AppointmentBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_books_an_appointment_and_dispatches_confirmation(): void
    {
        Bus::fake();

        $service = Service::factory()->create(['duration_minutes' => 30]);
        $professional = HealthProfessional::factory()->create();
        $scheduledAt = Carbon::now()->addDay()->setTime(9, 0)->format('Y-m-d H:i:s');

        $response = $this->postJson('/api/appointment', [
            'service_id' => $service->id,
            'health_professional_id' => $professional->id,
            'customer_email' => 'customer@example.com',
            'date' => $scheduledAt,
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['message' => 'Appointment created successfully'])
            ->assertJsonPath('data.service_id', $service->id)
            ->assertJsonPath('data.health_professional_id', $professional->id)
            ->assertJsonPath('data.customer_email', 'customer@example.com');

        $this->assertDatabaseHas('appointments', [
            'service_id' => $service->id,
            'health_professional_id' => $professional->id,
            'customer_email' => 'customer@example.com',
            'scheduled_at' => $scheduledAt,
        ]);

        Bus::assertDispatched(SendAppointmentConfirmationEmailJob::class, function ($job) {
            return $job->appointment instanceof Appointment
                && $job->appointment->customer_email === 'customer@example.com';
        });
    }

    public function test_it_blocks_overlapping_appointments_for_the_same_professional(): void
    {
        Bus::fake();

        $service = Service::factory()->create(['duration_minutes' => 60]);
        $professional = HealthProfessional::factory()->create();

        Appointment::factory()->create([
            'service_id' => $service->id,
            'health_professional_id' => $professional->id,
            'scheduled_at' => '2025-01-01 10:00:00',
        ]);

        $response = $this->postJson('/api/appointment', [
            'service_id' => $service->id,
            'health_professional_id' => $professional->id,
            'customer_email' => 'customer@example.com',
            'date' => '2025-01-01 10:30:00',
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment(['message' => 'This appointment is already booked.']);

        $this->assertDatabaseCount('appointments', 1);
    }
}
