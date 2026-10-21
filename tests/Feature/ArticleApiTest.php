<?php

use App\Models\Article;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

// ===============================================================================

test('未ログインだと401が返り、記事は保存されない', function () {
    $this->postJson('/api/articles', [
        'title' => '最初の記事',
        'body' => '本文です',
    ])->assertUnauthorized();

    expect(Article::count())->toBe(0);
});

// ===============================================================================

test('作成した記事が、ログインのユーザーのものとして保存される', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $this->postJson('/api/articles', [
        'title' => '最初の記事',
        'body' => '本文です',
    ]);

    $this->assertDatabaseHas('articles', [
        'user_id' => $user->id,
    ]);
});

// ===============================================================================

test('記事を作成できる', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/articles', [
        'title' => '最初の記事',
        'body' => '本文です',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.title', '最初の記事');

    $this->assertDatabaseHas('articles', ['title' => '最初の記事']);
});

// ===============================================================================

test('タイトルがないと422になる', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $this->postJson('/api/articles', ['body' => '本文です'])
        ->assertUnprocessable()                  // 422
        ->assertJsonValidationErrors(['title']); // errors に title がある
});

// ===============================================================================

test('タイトルが256文字だと422になる', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $this->postJson('/api/articles', [
        'title' => str_repeat('あ', 256),
        'body'  => '本文です',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});

// ===============================================================================
