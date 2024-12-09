<?php

namespace App\Contracts\Reservation;

use App\Contracts\EntitySchema;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DefaultReservationSchema extends EntitySchema
{
    /**
     * Create an instance of reservation schema.
     * 
     * @param string $subscriber_id
     * @param string $book_id
     * @param int $period
     * @param Carbon $expired_at
     */
    private function __construct(
        public readonly string $book_id,
        public readonly int $period,
        public readonly Carbon $expired_at,
        public readonly string $subscriber_id,
    ) {
        //
    }

    /**
     * Create an instance of reservation schema from arguments.
     * 
     * @param null|string $book_id
     * @param int $period
     * @param null|Carbon $expired_at
     * @param null|string $subscriber_id
     * @return DefaultReservationSchema
     */
    public static function create(
        string $book_id,
        string $subscriber_id,
        int $period,
        null|string|Carbon $expired_at = null,
    ): self {
        if ($expired_at === null) {
            $expired_at = Carbon::now()->addDays($period);
        } else if (is_string($expired_at)) {
            $expired_at = Carbon::parse($expired_at);
        }

        return new self(
            $book_id,
            $period,
            $expired_at,
            $subscriber_id,
        );
    }

    /**
     * Create an instance of reservation schema from request.
     * 
     * @param Request $request
     * @return DefaultReservationSchema
     */
    public static function fromRequest(Request $request): self
    {
        $expired_at = Carbon::now()->addDays((int) $request->input('period'));

        return new self(
            $request->input('book_id'),
            $request->input('period'),
            $expired_at,
            $request->input('subscriber_id'),
        );
    }

    /**
     * Convert the reservation schema to array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'book_id' => $this->book_id,
            'subscriber_id' => $this->subscriber_id,
            'period' => $this->period,
            'expired_at' => $this->expired_at
        ];
    }
}