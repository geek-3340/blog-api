<?php

test('記事を作成できる', function () {
    // １．APIにJSONでPOSTする
    $response = $this->postJson('/api/articles', [
        'title' => '最初の記事',
        'body' => '本文です',
    ]);

    // ２．レスポンスを確認する（201Created と返却JSON）
    $response->assertCreated()
        ->assertJsonPath('data.title', '最初の記事');

    // ３．DBに本当に保存されたか確認する
    $this->assertDatabaseHas('articles', ['title' => '最初の記事']);
});
