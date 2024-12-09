<?php

namespace App\Models;

use App\Enums\BookStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    /**
     * The "booted" method of the model.
     * Register the model event callbacks.
     * 
     */
    protected static function booted(): void
    {
        // TODO: It's not a performant approach. It must be handled by a scheduler.

        static::retrieved(function (Book $book) {
            $expired = $book->reservations()->whereDate('expired_at', '>=', now()->toDateString())->get();

            if ($expired->isEmpty()) {
                $book->changeStatus(BookStatus::ACCESSIBLE);
            }
        });
    }

    /**
     * Change the status of the book.
     * 
     * @param BookStatus $status
     * @return void
     */
    public function changeStatus(BookStatus $status): void
    {
        $this->status = $status;
        $this->save();
    }

    /**
     * Decorate the status of the book.
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn(int $status) => BookStatus::from($status),
            set: fn(BookStatus $status) => $status->value,
        );
    }

    /**
     * The reservation which has this book.
     * 
     * @return HasMany
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
