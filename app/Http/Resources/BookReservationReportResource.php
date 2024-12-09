<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookReservationReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'book' => new BookResource($this->resource),
            'number_of_reservation' => $this->number_of_reservation,
            'reserved_in_hours' => abs($this->reserved_in_hours),
        ];
    }
}
