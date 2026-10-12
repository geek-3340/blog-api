<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\ArticleRepositoryInterface;

class ArticleService
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
    ) {}

    public function create(array $data): Article
    {
        return $this->articleRepository->create($data);
    }
}
