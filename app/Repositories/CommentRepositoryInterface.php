<?php

declare(strict_types=1);

namespace App\Repositories;

interface CommentRepositoryInterface extends RepositoryInterface
{
    public function findAllWithAuthorPaginate(int|string $userId, int $limit = 15, array $fields = ['*']): array;
    public function findByUserId(int|string $userId, array $fields = ['*']): array;
    public function findByUserIdPaginate(int|string $userId, int $limit = 15, array $fields = ['*']): array;
}
