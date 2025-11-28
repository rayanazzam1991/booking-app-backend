<?php

namespace App\Jobs;

use App\Mail\AppointmentConfirmationMail;
use App\Models\Appointment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Batchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAppointmentConfirmationEmailJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly Appointment $appointment)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('appointment_confirmation_prepared', [
            'appointment_id' => $this->appointment->id,
            'to' => $this->appointment->customer_email,
            'service' => $this->appointment->service->name,
            'professional' => $this->appointment->healthProfessional->user?->name,
            'scheduled_at' => $this->appointment->scheduled_at,
            'summary' => 'Simulated confirmation email was prepared and logged only.',
        ]);

        // this will log the email.
        Mail::to($this->appointment->customer_email)
            ->send(new AppointmentConfirmationMail($this->appointment));
    }
}
