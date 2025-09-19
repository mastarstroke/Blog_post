<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $token = auth('api')->login($user);

        return $this->buildTokenResponse($token, $user);
    }

    public function login(array $credentials): ?array
    {
        if (! $token = auth('api')->attempt($credentials)) {
            return null;
        }

        $user = auth('api')->user();
        return $this->buildTokenResponse($token, $user);
    }

    public function logout(): void
    {
        auth('api')->logout();
    }

    public function refresh(): array
    {
        $token = auth('api')->refresh();
        $user = auth('api')->user();
        return $this->buildTokenResponse($token, $user);
    }

    protected function buildTokenResponse(string $token, $user): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user,
        ];
    }
}
