<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function sendResponse(
        mixed $data,
        ?string $message = null,
        bool $isOk = true,
        int $code = 200,
        mixed $pagination = null
    ): JsonResponse {

        $response = [
            'success' => $isOk ?? true,
            'message' => $message ?? 'Success',
            'data' => $data ?? null,
            'pagination' => $pagination ?? null,
        ];

        return response()->json($response, (int) $code);
    }
}
