<?php
namespace App\Contracts\Reporters\Reservation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

interface ReservationReporterInterface
{
    /**
     * Generate a report of reservation for an entity by its id.
     * 
     * @param string $uuid
     * @return AnonymousResourceCollection|JsonResource
     */
    public function report(string $id): AnonymousResourceCollection|JsonResource;

    /**
     * Get the entity which the reservation report is generated for.
     * 
     * @param string $id
     * @return Model
     */
    public function findById(string $id): Model;
}