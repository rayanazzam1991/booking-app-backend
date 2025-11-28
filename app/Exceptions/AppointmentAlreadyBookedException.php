<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AppointmentAlreadyBookedException extends Exception
{
    public function __construct(
        string $message = 'This appointment is already booked.'
    ) {
        parent::__construct($message);
    }

    public function render($request): JsonResponse
    {
        return ApiResponse::sendResponse(
            data: null,
            message: $this->getMessage(),
            isOk: false,
            code: Response::HTTP_UNPROCESSABLE_ENTITY); // Unprocessable Entity
    }
}
