<?php

namespace App\Jobs;

use App\Mail\AppointmentConfirmationMail;
use App\Models\Appointment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAppointmentConfirmationEmailJob implements ShouldQueue
{
    use Queueable;

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
        Log::info($this->appointment);
        Log::info("Simulated email for appointment: {$this->appointment->id}", [
            'to' => $this->appointment->customer_email,
            'service' => $this->appointment->service->name,
            'professional' => $this->appointment->healthProfessional->user->name,
            'schedule' => $this->appointment->scheduled_at,
        ]);


        Mail::to($this->appointment->customer_email)
            ->send(new AppointmentConfirmationMail($this->appointment));
    }
}
