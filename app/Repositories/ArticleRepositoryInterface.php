<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\User;

interface ArticleRepositoryInterface
{
    public function create(array $data, User $user): Article;

    public function update(Article $article, array $data): Article;

    public function delete(Article $article): void;
}
