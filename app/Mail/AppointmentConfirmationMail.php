<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public readonly Appointment $appointment)
    {

    }

    /**
     * Define message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Appointment Confirmation')
        );
    }

    /**
     * Define message content.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.appointments.confirmation',
            with: [
                'appointment' => $this->appointment,
                'service' => $this->appointment->service,
                'professional' => $this->appointment->healthProfessional,
                'customer' => $this->appointment->customer_email,
                'schedule' => $this->appointment->scheduled_at,
            ],
        );
    }

    /**
     * Attachments (none).
     */
    public function attachments(): array
    {
        return [];
    }
}
