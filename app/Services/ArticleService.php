<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use App\Repositories\ArticleRepositoryInterface;

class ArticleService
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
    ) {}

    public function create(array $data, User $user): Article
    {
        return $this->articleRepository->create($data, $user);
    }
}
