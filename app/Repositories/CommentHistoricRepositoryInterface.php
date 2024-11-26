<?php

declare(strict_types=1);

namespace App\Repositories;

interface CommentHistoricRepositoryInterface extends RepositoryInterface
{
    public function findByCommentId(int|string $commentId, array $fields = ['*']): array;
    public function findByCommentIdPaginate(int|string $commentId, int $limit = 15, array $fields = ['*']): array;
}
