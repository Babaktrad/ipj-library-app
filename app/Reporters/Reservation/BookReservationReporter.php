<?php

namespace App\Reporters\Reservation;

use App\Contracts\Reporters\Reservation\ReservationReporterInterface;
use App\Http\Resources\BookReservationReportResource;
use App\Models\Book;
use App\Services\Api\Books\BooksService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class BookReservationReporter implements ReservationReporterInterface
{
    /**
     * @param BooksService $subscribersService
     */
    public function __construct(private BooksService $booksService)
    {
        $booksService->setModel(new Book);
    }

    /**
     * Get the subscriber which the reservation is made for.
     * 
     * @param string $bookId
     * @return Model
     */
    public function findById(string $bookId): Model
    {
        return $this->booksService->findById($bookId);
    }

    /**
     * Generate a report of reservation for a book.
     * 
     * @param string $bookId
     * @return JsonResource
     */
    public function report(string $bookId): JsonResource
    {
        $book = $this->findById($bookId);
        $book['number_of_reservation'] = $this->countReservations($book);
        $book['reserved_in_hours'] = $this->countReservedInHours($book);

        return new BookReservationReportResource($book);
    }

    /**
     * Count the number of reservations for a given book.
     * 
     * @param Model $book
     * @return int
     */
    private function countReservations(Model $book): int
    {
        return $book->loadCount('reservations')->reservations_count;
    }

    /**
     * Count the number of reservations for a given book in hours.
     * 
     * @param Model $book
     * @return int
     */
    private function countReservedInHours(Model $book): int
    {
        $reservations = $book->load('reservations')->reservations;

        $reservedInHours = 0;

        foreach ($reservations as $reservation) {
            $reservedInHours += $reservation->expired_at->diffInHours($reservation->created_at);
        }

        $book->unsetRelation('reservations');

        return (int) $reservedInHours;
    }
}