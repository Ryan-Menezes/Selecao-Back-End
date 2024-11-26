<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CommentHistoricRepositoryInterface;

class CommentHistoricService implements ServiceInterface
{
    use BaseService;

    public function __construct(protected CommentHistoricRepositoryInterface $repository)
    {}

    public function findByCommentId(int|string $commentId, array $fields = ['*']): array
    {
        return $this->repository->findByCommentId($commentId, $fields);
    }

    public function findByCommentIdPaginate(int|string $commentId, int $limit = 15, array $fields = ['*']): array
    {
        return $this->repository->findByCommentIdPaginate($commentId, $limit, $fields);
    }
}
