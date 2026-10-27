<?php

use App\Models\Article;
use App\Models\User;

test('自分の記事を削除すると204が返り、DBからデータが消えている', function () {
    $user = User::factory()->create();
    $article = Article::factory()->for($user)->create();

    $this->actingAs($user)->deleteJson("/api/articles/{$article->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('articles', ['id' => $article->id]);
});

test('存在しない記事を削除すると404が返る', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->deleteJson("/api/articles/999")
        ->assertNotFound();
});

test('未ログインだと401が返り、DBの記事は残っている', function () {
    $user = User::factory()->create();
    $article = Article::factory()->for($user)->create();

    $this->deleteJson("/api/articles/{$article->id}")
        ->assertUnauthorized();

    $this->assertModelExists($article);
});

test(
    '他人の記事を削除しようとすると403が返り、DBの記事は残っている',
    function () {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $article = Article::factory()->for($user)->create();

        $this->actingAs($user2)->putJson("/api/articles/{$article->id}")->assertForbidden();

        $this->assertModelExists($article);
    }
);
