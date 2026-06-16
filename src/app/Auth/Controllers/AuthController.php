<?php

namespace App\Auth\Controllers;

use App\Auth\Enums\UserRole;
use App\Auth\Requests\LoginRequest;
use App\Auth\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $user = User::create([
            ...$payload,
            'role' => UserRole::User,
        ]);

        $token = Auth::guard('api')->login($user);

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $token = Auth::guard('api')->attempt($credentials);

        if ($token === false) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        /** @var User|null $user */
        $user = Auth::guard('api')->user();

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function me(): JsonResponse
    {
        return response()->json([
            'user' => Auth::guard('api')->user(),
        ]);
    }
}
