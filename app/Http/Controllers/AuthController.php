<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, AuthService $authService): JsonResponse
    {
        $result = $authService->register($request->validated());

        return response()->json([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ], 201);
    }

    public function login(LoginRequest $request, AuthService $authService): JsonResponse
    {
        $result = $authService->login($request->validated());

        return $result
            ? response()->json([
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
            ], 200)
            : response()->json(['message' => 'メールアドレスまたはパスワードが正しくありません'], 401);
    }

    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
    
    public function logout(Request $request, AuthService $authService): JsonResponse
    {
        $authService->logout($request->user());
        
        return response()->noContent();
    }
}
