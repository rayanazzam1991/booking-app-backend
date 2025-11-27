<?php

namespace App\Http\Controllers;

use App\Actions\GetServicesListAction;
use App\Helpers\ApiResponse;
use App\Http\Resources\ServicesListResource;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    public function __construct(
        private readonly GetServicesListAction $getServicesListAction
    ) {}

    public function index(): JsonResponse
    {
        $services = $this->getServicesListAction->handle();

        return ApiResponse::sendResponse(
            data: ServicesListResource::collection($services)
        );
    }
}
