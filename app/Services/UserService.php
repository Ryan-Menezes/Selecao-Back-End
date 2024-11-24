<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService implements ServiceInterface
{
    use BaseService;

    public function __construct(protected UserRepositoryInterface $repository)
    {}

    public function create(array $data): array
    {
        $data['password'] = Hash::make($data['password']);

        return $this->repository->create($data);
    }

    public function update(int|string $id, array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->repository->update($id, $data);
    }

    public function logout(int|string $id): void
    {
        $this->repository->logout($id);
    }

    public function login(string $email, string $password): string|null
    {
        return $this->repository->login($email, $password);
    }

    public function createTokenFor(int|string $id): string|null
    {
        return $this->repository->createTokenFor($id);
    }

    public function findByRememberToken(string $remember_token): array|null
    {
        return $this->repository->findByRememberToken($remember_token);
    }
}
