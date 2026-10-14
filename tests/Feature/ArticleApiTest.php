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

test('タイトルがないと422になる', function () {
    $this->postJson('/api/articles', ['body' => '本文です'])
        ->assertUnprocessable()                  // 422
        ->assertJsonValidationErrors(['title']); // errors に title がある
});

test('タイトルが256文字だと422になる', function () {
    $this->postJson('/api/articles', [
        'title' => str_repeat('あ', 256),
        'body'  => '本文です',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});
