<?php

namespace App\Reporters\Reservation;

use App\Contracts\Reporters\Reservation\ReservationReporterInterface;
use App\Http\Resources\ReservationResource;
use App\Models\Subscriber;
use App\Services\Api\Subscribers\SubscribersService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubscriberReservationReporter implements ReservationReporterInterface
{
    /**
     * @param SubscribersService $subscribersService
     */
    public function __construct(private SubscribersService $subscribersService)
    {
        $subscribersService->setModel(new Subscriber);
    }

    /**
     * Get the subscriber which the reservation is made for.
     * 
     * @param string $id
     * @return Model
     */
    public function findById(string $id): Model
    {
        return $this->subscribersService->findById($id);
    }

    public function report(string $id): AnonymousResourceCollection
    {
        $subscriber = $this->findById($id)->load('reservations.subscriber', 'reservations.book');
        return ReservationResource::collection($subscriber['reservations']);
    }
}