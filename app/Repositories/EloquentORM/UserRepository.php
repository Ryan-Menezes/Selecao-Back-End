<?php

declare(strict_types=1);

namespace App\Repositories\EloquentORM;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    public function logout(int|string $id): void
    {
        $this->model->find($id)?->tokens()->delete();
    }

    public function login(string $email, string $password): string|null
    {
        $user = $this->model->where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function createTokenFor(int|string $id): string|null
    {
        return $this->model->find($id)?->createToken('auth_token')->plainTextToken;
    }

    public function findByRememberToken(string $remember_token): array|null
    {
        return $this->model->where('remember_token', $remember_token)->first()?->toArray();
    }
}
