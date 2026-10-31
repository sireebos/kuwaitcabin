<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository
{
    protected Model $model;

    abstract public function getModel(): string;

    public function __construct()
    {
        $this->model = app($this->getModel());
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    public function findBy(string $field, $value): ?Model
    {
        return $this->model->where($field, $value)->first();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): bool
    {
        $model = $this->find($id);
        return $model ? $model->update($data) : false;
    }

    public function delete($id): bool
    {
        $model = $this->find($id);
        return $model ? $model->delete() : false;
    }

    public function paginate(int $perPage = 15)
    {
        return $this->model->paginate($perPage);
    }

    public function where(string $field, $value)
    {
        return $this->model->where($field, $value);
    }
}

