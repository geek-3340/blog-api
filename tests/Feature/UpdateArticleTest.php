<?php

use App\Models\Article;
use App\Models\User;

// ========================================================================================

test('記事を更新できる', function () {
    $user = User::factory()->create();
    $article = Article::factory()->for($user)->create(['title' => '古いタイトル']);

    $response = $this->actingAs($user)->putJson("/api/articles/{$article->id}", [
        'title' => '新しいタイトル',
        'body' => '新しい本文',
    ]);

    $response->assertOk()->assertJsonPath('data.title', '新しいタイトル');

    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
        'title' => '新しいタイトル'
    ]);
});

// ========================================================================================

test('存在しない記事を更新しようとすると404', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->putJson("/api/articles/999", [
        'title' => 'x',
        'body' => 'y',
    ])->assertNotFound();
});

// ========================================================================================

test('titleが256文字だと422になる', function () {
    $user = User::factory()->create();
    $article = Article::factory()->for($user)->create(['title' => '古いタイトル']);

    $this->actingAs($user)->putJson("/api/articles/{$article->id}", [
        'title' => str_repeat('あ', 256),
        'body' => '新しい本文',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});

// ========================================================================================

test('未ログインだと401が返る', function () {
    $user = User::factory()->create();
    $article = Article::factory()->for($user)->create();

    $response = $this->putJson("/api/articles/{$article->id}", [
        'title' => '新しいタイトル',
    ]);

    $response->assertUnauthorized();
});

// ========================================================================================

test(
    '他人の記事を更新しようとすると403が返り、DBの記事は変わっていない',
    function () {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $article = Article::factory()->for($user)->create();

        $response = $this->actingAs($user2)->putJson("/api/articles/{$article->id}", [
            'title' => '新しいタイトル',
        ]);

        $response->assertForbidden();
    }
);

// ========================================================================================