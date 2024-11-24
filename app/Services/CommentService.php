<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CommentRepositoryInterface;

class CommentService implements ServiceInterface
{
    use BaseService;

    public function __construct(protected CommentRepositoryInterface $repository)
    {}

    public function findByUserId(int|string $userId): array
    {
        return $this->repository->findByUserId($userId);
    }
}
