<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $payload = $this->authService->register($request->validated());

        return response()->json([
            'access_token' => $payload['access_token'],
            'token_type' => $payload['token_type'],
            'expires_in' => $payload['expires_in'],
            'user' => new UserResource($payload['user']),
        ], 201);
    }

    public function login(AuthLoginRequest $request): JsonResponse
    {
        $payload = $this->authService->login($request->validated());

        if (! $payload) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'access_token' => $payload['access_token'],
            'token_type' => $payload['token_type'],
            'expires_in' => $payload['expires_in'],
            'user' => new UserResource($payload['user']),
        ]);
    }

    public function me(): UserResource
    {
        return new UserResource(auth('api')->user());
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(): JsonResponse
    {
        $payload = $this->authService->refresh();

        return response()->json([
            'access_token' => $payload['access_token'],
            'token_type' => $payload['token_type'],
            'expires_in' => $payload['expires_in'],
            'user' => new UserResource($payload['user']),
        ]);
    }
}
