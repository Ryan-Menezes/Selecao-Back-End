<?php

declare(strict_types=1);

namespace App\Repositories\EloquentORM;

use App\Models\CommentHistoric;
use App\Repositories\CommentHistoricRepositoryInterface;

class CommentHistoricRepository extends BaseRepository implements CommentHistoricRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new CommentHistoric());
    }

    public function findByCommentId(int|string $commentId, array $fields = ['*']): array
    {
        return $this->model->select($fields)->where('comment_id', $commentId)->get()->toArray();
    }

    public function findByCommentIdPaginate(int|string $commentId, int $limit = 15, array $fields = ['*']): array
    {
        return $this->model->select($fields)->where('comment_id', $commentId)->paginate($limit)->toArray();
    }
}
