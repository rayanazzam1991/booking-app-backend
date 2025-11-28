<?php

namespace App\Services;

use App\DTOs\CreateAppointmentDTO;
use App\Exceptions\AppointmentAlreadyBookedException;
use App\Jobs\SendAppointmentConfirmationEmailJob;
use App\Models\Appointment;
use App\Models\Service;
use Carbon\Carbon;

class AppointmentService
{
    /**
     * @throws AppointmentAlreadyBookedException
     */
    public function book(CreateAppointmentDTO $appointmentDTO): Appointment
    {
        $service = Service::query()->findOrFail($appointmentDTO->serviceId);

        $requestedStart = Carbon::parse($appointmentDTO->scheduledAt);
        $requestedEnd = (clone $requestedStart)->addMinutes(max($service->duration_minutes ?? 0, 0));

        $hasOverlap = Appointment::query()
            ->with('service')
            ->where('health_professional_id', $appointmentDTO->healthProfessionalId)
            ->get()
            ->contains(function (Appointment $appointment) use ($requestedStart, $requestedEnd) {
                $existingStart = Carbon::parse($appointment->scheduled_at);
                $existingEnd = (clone $existingStart)->addMinutes(max($appointment->service->duration_minutes ?? 0, 0));

                return $this->overlaps($requestedStart, $requestedEnd, $existingStart, $existingEnd);
            });

        if ($hasOverlap) {
            throw new AppointmentAlreadyBookedException;
        }

        $appointment =  Appointment::query()->create($appointmentDTO->toArray());

        $this->notifyCustomerWithAppointment($appointment);

        return $appointment;
    }

    private function notifyCustomerWithAppointment(Appointment $appointment)
    {
        SendAppointmentConfirmationEmailJob::dispatch($appointment);
    }

    private function overlaps(Carbon $requestedStart, Carbon $requestedEnd, Carbon $existingStart, Carbon $existingEnd): bool
    {
        return $requestedStart < $existingEnd && $requestedEnd > $existingStart;
    }
}
