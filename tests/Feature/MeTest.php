<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('ログイン中のユーザー情報を取得できる', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $this->getJson('/api/me')
        ->assertOk()
        ->assertJsonPath('data.email', $user->email);
});

test('未ログインだと401が返る', function () {
    $this->getJson('/api/me')->assertUnauthorized();
});

test('ログインで得たトークンを付けると/meにアクセスできる', function () {
    User::factory()->create(['email' => 'taro@example.com', 'password' => 'password123']);

    $token = $this->postJson('/api/login', [
        'email' => 'taro@example.com',
        'password' => 'password123',
    ])->json('token');

    $this->withToken($token)    // Authorization: Bearer <token> を付ける
        ->getJson('/api/me')
        ->assertOk();
});
