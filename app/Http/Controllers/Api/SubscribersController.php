<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Subscribers\DefaultSubscriberSchema;
use App\Http\Requests\Subscriber\SubscriberCreateRequest;
use App\Http\Requests\Subscriber\SubscriberUpdateRequest;
use App\Http\Resources\SubscriberResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Contracts\Services\EntityServiceInterface;
use App\Services\Api\Subscribers\SubscribersService;

class SubscribersController extends ApiBaseController
{
    /**
     * @var SubscribersService $subscribersService
     * @param EntityServiceInterface $subscribersServices
     */
    public function __construct(private EntityServiceInterface $subscribersService)
    {
        //
    }

    /**
     * List all subscribers.
     * 
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        try {
            $susbscribers = SubscriberResource::collection($this->subscribersService->all());

            return $this->succeeded($susbscribers);
        } catch (\Throwable $th) {
            return $this->failed();
        }
    }

    /**
     * Store a new subscriber for authenticated user.
     * 
     * @param SubscriberCreateRequest $request
     * @return JsonResponse
     */
    public function store(SubscriberCreateRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = DefaultSubscriberSchema::fromRequest($request);
            $subscriber = $this->subscribersService->store($data);
            $this->subscribersService->subscribe($subscriber->id);

            DB::commit();

            return $this->succeeded($subscriber, __('messages.subscribers.create.succeeded'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->failed(__('messages.subscribers.create.failed'));
        }
    }
    /**
     * Update the subscriber belongs to authenticated user.
     * 
     * @param SubscriberUpdateRequest $request
     * @return JsonResponse
     */
    public function update(SubscriberUpdateRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = DefaultSubscriberSchema::fromRequest($request);
            $this->subscribersService->update($data);

            DB::commit();

            return $this->succeeded(null, __('messages.subscribers.update.succeeded'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->failed(__('messages.subscribers.update.failed'));
        }
    }

    /**
     * Delete the subscriber belongs to authenticated user.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->subscribersService->delete($request->id);

            DB::commit();

            return $this->succeeded(null, __('messages.subscribers.delete.succeeded'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->failed(__('messages.subscribers.delete.failed'));
        }
    }
}
