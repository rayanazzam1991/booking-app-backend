<?php

namespace App\Http\Controllers;

use App\DTOs\CreateAppointmentDTO;
use App\Helpers\ApiResponse;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    public function __construct(
        private readonly AppointmentService $appointmentService
    ) {}

    public function create(CreateAppointmentRequest $appointmentRequest): JsonResponse
    {
        $dataFromRequest = CreateAppointmentDTO::fromRequest($appointmentRequest->validated());

        $appointment = $this->appointmentService->book($dataFromRequest);

        return ApiResponse::sendResponse(
            data: AppointmentResource::make($appointment),
            message: 'Appointment created successfully',
            code: Response::HTTP_CREATED);

    }
}
