<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasUuids;

    protected $guarded = ['id'];
    protected $table = 'reservation_histories';

    protected $casts = [
        'expired_at' => 'date'
    ];

    /**
     * The book which belongs to this reservation.
     * 
     * @return BelongsTo
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * The subscriber which this reservation belongs to.
     * 
     * @return BelongsTo
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }
}
