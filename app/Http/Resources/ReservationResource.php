<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "subscriber" => new SubscriberResource($this->whenLoaded('subscriber')),
            'book' => new BookResource($this->whenLoaded('book')),
            'expired_at' => $this->expired_at,
            'period' => $this->period,
        ];
    }
}
