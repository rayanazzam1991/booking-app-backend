<?php

namespace App\Services;

use App\DTOs\CreateAppointmentDTO;
use App\Exceptions\AppointmentAlreadyBookedException;
use App\Models\Appointment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AppointmentService
{
    /**
     * @throws AppointmentAlreadyBookedException
     */
    public function book(CreateAppointmentDTO $appointmentDTO): Appointment
    {
        $service = Service::query()->findOrFail($appointmentDTO->serviceId);

        $requestedStart = Carbon::parse($appointmentDTO->scheduledAt);
        $requestedEnd = (clone $requestedStart)->addMinutes($service->duration);

        Log::info('times', [$requestedStart, $requestedEnd]);

        $exists = Appointment::query()->where('health_professional_id', $appointmentDTO->healthProfessionalId)
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where(function ($q) use ($requestedStart, $requestedEnd) {
                $q->where('appointments.scheduled_at', '<=', $requestedEnd)
                    ->whereRaw('DATE_ADD(appointments.scheduled_at, INTERVAL services.duration_minutes MINUTE) >= ?', [$requestedStart]);
            })
            ->exists();

        if ($exists) {
            throw new AppointmentAlreadyBookedException;
        }

        return Appointment::query()->create($appointmentDTO->toArray());
    }
}
