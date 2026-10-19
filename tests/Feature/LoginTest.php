<?php

use App\Models\User;

test('正しいメールアドレスとパスワードでログインするとトークンが返る', function () {
    User::factory()->create([
        'email'=>'taro@example.com',
        'password'=>'password123',
    ]);

    $this->postJson('/api/login',[
        'email'=>'taro@example.com',
        'password'=>'password123',
    ])->assertOk()->assertJsonStructure(['token']);
});

test('誤ったパスワードでログインすると401が返る', function () {
    User::factory()->create([
        'email' => 'taro@example.com',
        'password' => 'password123',
    ]);

    $this->postJson('/api/login', [
        'email' => 'taro@example.com',
        'password' => 'password456',
    ])->assertUnauthorized();
});

test('存在しないメールアドレスでログインすると401が返る', function () {
    $this->postJson('/api/login', [
        'email' => 'nobody@example.com',
        'password' => 'password123',
    ])->assertUnauthorized();
}); 
