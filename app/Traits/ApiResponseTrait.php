<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function success(?string $message, mixed $data = [], int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error(?string $message, int $code, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
