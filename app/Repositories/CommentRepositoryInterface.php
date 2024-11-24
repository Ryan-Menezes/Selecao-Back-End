<?php

declare(strict_types=1);

namespace App\Repositories;

interface CommentRepositoryInterface extends RepositoryInterface
{
    public function findByUserId(int|string $userId): array;
}
