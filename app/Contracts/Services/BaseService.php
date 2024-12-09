<?php

namespace App\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\EntitySchema;
use Illuminate\Support\Collection;

abstract class BaseService implements EntityServiceInterface
{
    protected ?Model $model = null;

    /**
     * Get Eloquent model which the service is composed with.
     * 
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Set Eloquent model which the service is composed with.
     * 
     * @param Model $model
     * @return BaseService
     */
    public function setModel(Model $model): static
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get all records of the model.
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get a record by id.
     * 
     * @param string $id
     * @return Model
     */
    public function findById(string $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create and store a record of the model.
     * 
     * @param EntitySchema $request
     * @return Model
     */
    public function store(EntitySchema $request): Model
    {
        return $this->model->create($request->toArray());
    }

    /**
     * Update a record of the model.
     * 
     * @param EntitySchema $request
     * @return bool
     */
    public function update(EntitySchema $request): bool
    {
        $found = $this->findById($request->id);
        return $found->update($request->toArray());
    }

    /**
     * Delete a record of the model by given id.
     * 
     * @param string $id
     * @return bool|null
     */
    public function delete(string $id): bool|null
    {
        $found = $this->findById($id);
        return $found->delete();
    }
}