<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Enter valid email and password'], 401);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        User::create($request->only('name', 'email', 'password'));
        return response()->json('User created', 201);
    }

    public function me(): JsonResponse
    {
        $user = $this->guard()->user();

        return response()->json($user);
    }

    public function logout(): JsonResponse
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $this->guard()->factory()->getTTL() * 60,
        ]);
    }

    public function guard(): Guard
    {
        return Auth::guard('api');
    }
}
