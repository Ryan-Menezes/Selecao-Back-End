<?php

declare(strict_types=1);

namespace App\Repositories\EloquentORM;

use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    public function __construct(protected Model $model)
    {}

    public function findAll(array $fields = ['*']): array
    {
        return $this->model->select($fields)->get()->toArray();
    }

    public function findAllPaginate(int $limit = 15, array $fields = ['*']): array
    {
        return $this->model->select($fields)->paginate($limit)->toArray();
    }

    public function findById(int|string $id): array|null
    {
        return $this->model->find($id)?->toArray();
    }

    public function create(array $data): array
    {
        return $this->model->create($data)->toArray();
    }

    public function update(int|string $id, array $data): bool
    {
        $model = $this->model->find($id);

        if (!$model) return false;

        return $model->update($data);
    }

    public function delete(int|string $id): bool
    {
        $model = $this->model->find($id);

        if (!$model) return false;

        return $model->delete();
    }
}
