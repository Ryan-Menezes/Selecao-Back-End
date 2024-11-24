<?php

declare(strict_types=1);

namespace App\Repositories\EloquentORM;

use App\Models\Comment;
use App\Repositories\CommentRepositoryInterface;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Comment());
    }

    public function findByUserId(int|string $userId): array
    {
        return $this->model->where('user_id', $userId)->get()->toArray();
    }
}
