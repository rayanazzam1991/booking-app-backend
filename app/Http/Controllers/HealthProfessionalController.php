<?php

namespace App\Http\Controllers;

use App\Actions\GetHealthProfessionalListAction;
use App\Helpers\ApiResponse;
use App\Http\Resources\HealthProfessionalsListResource;
use Illuminate\Http\JsonResponse;

class HealthProfessionalController extends Controller
{
    public function __construct(
        private readonly GetHealthProfessionalListAction $getHealthProfessionalListAction
    ) {}

    public function index(): JsonResponse
    {
        $healthProfessionals = $this->getHealthProfessionalListAction->handle();

        return ApiResponse::sendResponse(
            data: HealthProfessionalsListResource::collection($healthProfessionals)
        );
    }
}
