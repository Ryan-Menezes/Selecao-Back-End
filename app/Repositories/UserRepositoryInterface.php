<?php

declare(strict_types=1);

namespace App\Repositories;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function logout(int|string $id): void;
    public function login(string $email, string $password): string|null;
    public function createTokenFor(int|string $id): string|null;
    public function findByRememberToken(string $remember_token): array|null;
}
