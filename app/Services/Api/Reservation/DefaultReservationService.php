<?php

namespace App\Services\Api\Reservation;

use App\Contracts\EntitySchema;
use App\Contracts\Services\BaseService;
use App\Contracts\Reporters\Reservation\ReservationReporterInterface;
use App\Contracts\Services\ReservationServiceInterface;
use App\Enums\BookStatus;
use App\Exceptions\Reservation\BookIsReservedException;
use App\Models\Book;
use App\Services\Api\Books\BooksService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;

class DefaultReservationService extends BaseService implements ReservationServiceInterface
{
    /**
     * @param BooksService $booksService
     */
    public function __construct(private BooksService $booksService)
    {
        $booksService->setModel(new Book);
    }

    /**
     * Reserve a book for a subscriber.
     * 
     * @param EntitySchema $request
     * @throws BookIsReservedException
     * @return void
     */
    public function reserve(EntitySchema $request): void
    {
        if ($this->isReserved($request->book_id)) {
            throw new BookIsReservedException(__('messages.reservation.book.is_reserved'));
        } else {
            $this->model->create([
                'book_id' => $request->book_id,
                'subscriber_id' => $request->subscriber_id,
                'expired_at' => $request->expired_at,
                'period' => $request->period,
            ]);

            $this->booksService->changeStatus($request->book_id, BookStatus::RESERVED);
        }
    }

    /**
     * Make a book as accessible.
     * 
     * @param string $bookId
     * @return void
     */
    public function free(string $bookId): void
    {
        $this->booksService->changeStatus($bookId, BookStatus::ACCESSIBLE);
        $now = Carbon::now()->toDateString();
        $this->model->where('book_id', $bookId)->update(['expired_at' => $now]);
    }

    /**
     * Generate a reservation report for a book.
     * 
     * @param string $bookId
     * @param ReservationReporterInterface $bookReporter
     * @return AnonymousResourceCollection|JsonResource
     */
    public function book(string $bookId, ReservationReporterInterface $bookReporter): AnonymousResourceCollection|JsonResource
    {
        return $bookReporter->report($bookId);
    }

    /**
     * Generate a reservation report for a subscriber.
     * 
     * @param string $subscriberId
     * @param ReservationReporterInterface $subscriberReporter
     * @return AnonymousResourceCollection|JsonResource
     */
    public function history(string $subscriberId, ReservationReporterInterface $subscriberReporter): AnonymousResourceCollection|JsonResource
    {
        return $subscriberReporter->report($subscriberId);
    }

    /**
     * Check if a book is reserved.
     * 
     * @param string $bookId
     * @return bool
     */
    public function isReserved(string $bookId): bool
    {
        return $this->booksService->isReserved($bookId);
    }
}
