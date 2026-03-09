<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    use ApiResponseTrait;

    public function toResponse($request): JsonResponse|Response
    {
        $user = $request->user();

        $token = $user?->createToken('auth_token')->plainTextToken;

        return $this->success(
            message: 'Login successful',
            data: [
                'user' => UserResource::make($user),
                'token' => $token,
            ]
        );
    }
}
