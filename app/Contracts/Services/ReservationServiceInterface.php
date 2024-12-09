<?php

namespace App\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\EntitySchema;
use App\Contracts\Reporters\Reservation\ReservationReporterInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

interface ReservationServiceInterface
{
    /**
     * Reserve a book for a subscriber for a given number of days.
     * 
     * @param EntitySchema $request
     * @return void
     */
    public function reserve(EntitySchema $request): void;

    /**
     * Free a book of reservation.
     * 
     * @param string $bookId
     * @return void
     */
    public function free(string $bookId): void;

    /**
     * Get the history of reservations for a subscriber.
     * 
     * @param string $subscriberId
     * @param ReservationReporterInterface $subscriberReporter
     * @return AnonymousResourceCollection|JsonResource
     */
    public function history(string $subscriberId, ReservationReporterInterface $subscriberReporter): AnonymousResourceCollection|JsonResource;

    /**
     * Get the reservation report for a book.
     * 
     * @param string $bookId
     * @param ReservationReporterInterface $bookReporter
     * @return AnonymousResourceCollection|JsonResource
     */
    public function book(string $bookId, ReservationReporterInterface $bookReporter): AnonymousResourceCollection|JsonResource;

    /**
     * Check if a book is reserved.
     * 
     * @param string $bookId
     * @return bool
     */
    public function isReserved(string $bookId): bool;
}