<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Services\Auth\AuthService;
use App\Services\Auth\Exceptions\InvalidCredentialsException;

class AuthController extends Controller
{
    public function login(LoginRequest $request, AuthService $service)
    {
        $credentials = $request->validated();

        try {
            $token = $service->retrieveToken($credentials['email'], $credentials['password']);
        } catch (InvalidCredentialsException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        return response()->json(['token' => $token]);
    }
}
