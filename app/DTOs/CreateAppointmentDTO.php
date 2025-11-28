<?php

namespace App\DTOs;

use Illuminate\Http\Request;

readonly class CreateAppointmentDTO
{
    public function __construct(
        public int $serviceId,
        public int $healthProfessionalId,
        public string $scheduledAt,
        public string $customerEmail,
    ) {}

    /**
     * Static factory to build a DTO from an HTTP request.
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            serviceId: (int) $data['service_id'],
            healthProfessionalId: (int) $data['health_professional_id'],
            scheduledAt: $data['date'],
            customerEmail: $data['customer_email'],
        );
    }

    /**
     * Converts DTO into a database-ready array.
     */
    public function toArray(): array
    {
        return [
            'service_id' => $this->serviceId,
            'health_professional_id' => $this->healthProfessionalId,
            'scheduled_at' => $this->scheduledAt,
            'customer_email' => $this->customerEmail,
        ];
    }
}
