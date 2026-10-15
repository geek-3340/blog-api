<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('ユーザー登録するとユーザーが作成されトークンが返る', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Taro',
        'email' => 'taro@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertCreated()                     // 201
        ->assertJsonStructure([
            'user' => ['id', 'name', 'email'],
            'token',
        ])
        ->assertJsonMissingPath('user.password');  // パスワードを返していないこと

    $this->assertDatabaseHas('users', ['email' => 'taro@example.com']);

    // 平文のまま保存されていないこと（＝ハッシュ化されていること）
    $user = User::where('email', 'taro@example.com')->first();
    expect($user->password)->not->toBe('password123');
    expect(Hash::check('password123', $user->password))->toBeTrue();
});

test('既に使われているメールアドレスでは登録できない', function () {
    User::factory()->create(['email' => 'taro@example.com']);

    $this->postJson('/api/register', [
        'name' => 'Taro',
        'email' => 'taro@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertUnprocessable()                      // 422
        ->assertJsonValidationErrors(['email']);
});
