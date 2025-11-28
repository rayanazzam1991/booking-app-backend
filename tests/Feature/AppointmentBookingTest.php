<?php

use App\Jobs\SendAppointmentConfirmationEmailJob;
use App\Models\Appointment;
use App\Models\HealthProfessional;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;

uses(RefreshDatabase::class);

it('books an appointment and queues a confirmation email job', function () {
    Bus::fake();

    $service = Service::factory()->create([
        'duration_minutes' => 30,
    ]);
    $professional = HealthProfessional::factory()->create();
    $scheduledAt = Carbon::now()->addDay()->setTime(10, 0);

    $response = $this->postJson('/api/appointment', [
        'service_id' => $service->id,
        'health_professional_id' => $professional->id,
        'customer_email' => 'patient@example.com',
        'date' => $scheduledAt->toDateTimeString(),
    ]);

    $response
        ->assertCreated()
        ->assertJson([
            'success' => true,
            'message' => 'Appointment created successfully',
        ])
        ->assertJsonPath('data.customer_email', 'patient@example.com')
        ->assertJsonPath('data.health_professional_id', $professional->id)
        ->assertJsonPath('data.service_id', $service->id);

    $this->assertDatabaseHas('appointments', [
        'service_id' => $service->id,
        'health_professional_id' => $professional->id,
        'customer_email' => 'patient@example.com',
        'scheduled_at' => $scheduledAt->toDateTimeString(),
    ]);

    $appointment = Appointment::first();

    Bus::assertDispatched(SendAppointmentConfirmationEmailJob::class, function ($job) use ($appointment) {
        return $job->appointment->is($appointment);
    });
});

it('prevents booking a slot that overlaps an existing appointment', function () {
    $service = Service::factory()->create([
        'duration_minutes' => 45,
    ]);
    $professional = HealthProfessional::factory()->create();

    $existingStart = Carbon::now()->addDay()->setTime(9, 0);

    Appointment::factory()->create([
        'service_id' => $service->id,
        'health_professional_id' => $professional->id,
        'scheduled_at' => $existingStart->toDateTimeString(),
    ]);

    $response = $this->postJson('/api/appointment', [
        'service_id' => $service->id,
        'health_professional_id' => $professional->id,
        'customer_email' => 'second@example.com',
        'date' => $existingStart->copy()->addMinutes(15)->toDateTimeString(),
    ]);

    $response
        ->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'This appointment is already booked.',
        ]);

    $this->assertDatabaseCount('appointments', 1);
});

it('validates required appointment details', function () {
    $response = $this->postJson('/api/appointment', [
        'service_id' => null,
        'health_professional_id' => null,
        'customer_email' => 'not-an-email',
        'date' => Carbon::now()->subDay()->toDateTimeString(),
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'service_id',
            'health_professional_id',
            'customer_email',
            'date',
        ]);
});
