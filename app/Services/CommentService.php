<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CommentRepositoryInterface;

class CommentService implements ServiceInterface
{
    use BaseService;

    public function __construct(protected CommentRepositoryInterface $repository)
    {}

    public function findAllWithAuthorPaginate(int|string $userId, int $limit = 15, array $fields = ['*']): array
    {
        return $this->repository->findAllWithAuthorPaginate($userId, $limit, $fields);
    }

    public function findByUserId(int|string $userId, array $fields = ['*']): array
    {
        return $this->repository->findByUserId($userId, $fields);
    }

    public function findByUserIdPaginate(int|string $userId, int $limit = 15, array $fields = ['*']): array
    {
        return $this->repository->findByUserIdPaginate($userId, $limit, $fields);
    }
}
