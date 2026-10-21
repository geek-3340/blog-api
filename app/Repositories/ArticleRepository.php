<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\User;
use Override;

class ArticleRepository implements ArticleRepositoryInterface
{
    #[Override]
    public function create(array $data, User $user): Article
    {
        return $user->articles()->create($data);
    }
}
