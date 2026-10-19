<?php

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

test('ログアウトすると204が返り、トークンがDBから削除されている', function () {
    User::factory()->create(['email' => 'taro@example.com', 'password' => 'password123']);

    $token = $this->postJson('/api/login', [
        'email' => 'taro@example.com',
        'password' => 'password123',
    ])->json('token');

    $this->withToken($token)
        ->postJson('/api/logout')
        ->assertNoContent();

    expect(PersonalAccessToken::findToken($token))->toBeNull();
});

test('未ログインだと401が返る', function () {
    $this->postJson('/api/logout')
        ->assertUnauthorized();
});
