<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

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
}
