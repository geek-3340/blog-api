<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {}

    /**
     * @return array{user: User, token: string}
     */
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $user = $this->users->create($data);
            $token = $this->users->createToken($user, 'auth_token');

            return ['user' => $user, 'token' => $token];
        });
    }

    public function login(array $data): ?array
    {
        $user = $this->users->findByEmail($data['email']);

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return null;
        }

        $token = $this->users->createToken($user, 'auth_token');

        return ['user' => $user, 'token' => $token];
    }
}
