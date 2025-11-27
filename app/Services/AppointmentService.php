<?php

namespace App\Services;

use App\DTOs\CreateAppointmentDTO;
use App\Models\Appointment;

class AppointmentService
{
    public function book(CreateAppointmentDTO $appointmentDTO) : Appointment
    {
        return Appointment::query()->create($appointmentDTO->toArray());
    }
}
