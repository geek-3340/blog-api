<?php

namespace App\Repositories;

use App\Models\Article;
use Override;

class ArticleRepository implements ArticleRepositoryInterface
{
    #[Override]
    public function create(array $data): Article
    {
        return Article::create($data);
    }
}
