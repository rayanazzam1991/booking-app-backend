<?php

namespace App\Http\Controllers;

use App\Actions\GetServiceHealthProfessionalListAction;
use App\Actions\GetServicesListAction;
use App\Helpers\ApiResponse;
use App\Http\Resources\HealthProfessionalsListResource;
use App\Http\Resources\ServicesListResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    public function __construct(
        private readonly GetServicesListAction $getServicesListAction,
        private readonly GetServiceHealthProfessionalListAction $getServiceHealthProfessionalListAction,
    ) {}

    public function index(): JsonResponse
    {
        $services = $this->getServicesListAction->handle();

        return ApiResponse::sendResponse(
            data: ServicesListResource::collection($services)
        );
    }

    public function healthProfessionals(Service $service): JsonResponse
    {
        $professionals = $this->getServiceHealthProfessionalListAction->handle($service);

        return ApiResponse::sendResponse(
            data: HealthProfessionalsListResource::collection($professionals)
        );
    }
}
