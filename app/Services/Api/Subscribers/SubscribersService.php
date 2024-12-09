<?php

namespace App\Services\Api\Subscribers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\EntitySchema;
use App\Contracts\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

class SubscribersService extends BaseService
{

    /**
     * Create and store a new subscriber.
     * 
     * @param EntitySchema $request
     * @return Model
     */
    public function store(EntitySchema $request): Model
    {
        return $this->model->create([
            'name' => $request->name,
            'national_id' => $request->national_id,
            'subscription_expire_date' => $request->subscription_expire_date,
        ]);
    }

    /**
     * Update a subscriber.
     * 
     * @param EntitySchema $request
     * @return bool
     */
    public function update(EntitySchema $request): bool
    {
        $found = $this->findById($request->id);

        return $found->update(Arr::except($request->toArray(), ['id']));
    }

    /**
     * Save a subscriber for a user.
     * 
     * @param string $id
     * @param mixed $user
     * @return void
     */
    public function subscribe(string $id, ?User $user = null)
    {
        $found = $this->findById($id);

        if ($user) {
            $user->subscribe()->save($found);
            return;
        }

        resolve('api-auth')->user()->subscribe()->save($found);
    }
}