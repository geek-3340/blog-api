<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\User;

interface ArticleRepositoryInterface
{
    public function create(array $data, User $user): Article;
}
