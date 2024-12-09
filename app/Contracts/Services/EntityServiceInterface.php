<?php

namespace App\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\EntitySchema;
use Illuminate\Support\Collection;

interface EntityServiceInterface
{
    /**
     * Get all records of the model.
     * 
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get a record by id.
     * 
     * @param string $id
     * @return Model
     */
    public function findById(string $id): Model;

    /**
     * Create and store a record of the model.
     * 
     * @param EntitySchema $request
     * @return Model
     */
    public function store(EntitySchema $request): Model;

    /**
     * Update a record of the model.
     * 
     * @param EntitySchema $request
     * @return bool
     */
    public function update(EntitySchema $request): bool;

    /**
     * Delete a record of the model by given id.
     * 
     * @param string $id
     * @return bool|null
     */
    public function delete(string $id): bool|null;
}