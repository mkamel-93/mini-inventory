<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile.
     */
    public function show(Request $request): JsonResponse
    {
        return $this->success(
            message: 'Profile retrieved successfully',
            data: UserResource::make($request->user())
        );
    }

    /**
     * Log the user out by revoking their current access token.
     */
    public function logout(Request $request): JsonResponse
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
