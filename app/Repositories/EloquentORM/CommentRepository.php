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

    public function findAllWithAuthorPaginate(int|string $userId, int $limit = 15, array $fields = ['*']): array
    {
        return $this->model->select($fields)->with('author')->paginate($limit)->toArray();
    }

    public function findByUserId(int|string $userId, array $fields = ['*']): array
    {
        return $this->model->select($fields)->where('user_id', $userId)->get()->toArray();
    }

    public function findByUserIdPaginate(int|string $userId, int $limit = 15, array $fields = ['*']): array
    {
        return $this->model->select($fields)->where('user_id', $userId)->paginate($limit)->toArray();
    }
}
