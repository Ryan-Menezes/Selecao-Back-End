<?php

declare(strict_types=1);

namespace App\Services;

trait BaseService
{
    public function findAll(array $fields = ['*']): array
    {
        return $this->repository->findAll($fields);
    }

    public function findAllPaginate(int $limit = 15, array $fields = ['*']): array
    {
        return $this->repository->findAllPaginate($limit, $fields);
    }

    public function findById(int|string $id): array|null
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): array
    {
        return $this->repository->create($data);
    }

    public function update(int|string $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int|string $id): bool
    {
        return $this->repository->delete($id);
    }
}
