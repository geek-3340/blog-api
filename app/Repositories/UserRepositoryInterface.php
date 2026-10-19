<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function createToken(User $user, string $tokenName): string;

    public function findByEmail(string $email):?User;
}
