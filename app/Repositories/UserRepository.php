<?php

namespace App\Repositories;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Override;

class UserRepository implements UserRepositoryInterface
{
    #[Override]
    public function create(array $data): User
    {
        return User::create($data);
    }

    #[Override]
    public function createToken(User $user, string $tokenName): string
    {
        return $user->createToken($tokenName)->plainTextToken;
    }

    #[Override]
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    #[Override]
    public function deleteToken(User $user): void
    {
        $token=$user->currentAccessToken();

        if($token instanceof PersonalAccessToken){
            $token->delete();
        }
    }
}
