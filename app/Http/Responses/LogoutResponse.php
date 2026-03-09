<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class LogoutResponse implements LogoutResponseContract
{
    use ApiResponseTrait;

    public function toResponse($request): JsonResponse|Response
    {
        $user = $request->user();
        if ($user) {
            $user->currentAccessToken()->delete();
        }

        return $this->success(
            message: 'Logged out successfully',
            data: null
        );
    }
}
