<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Reservation\DefaultReservationSchema;
use App\Contracts\Services\ReservationServiceInterface;
use App\Exceptions\Reservation\BookIsReservedException;
use App\Http\Requests\Reservation\BookReservationHistoryRequest;
use App\Http\Requests\Reservation\ReservationFreeRequest;
use App\Http\Requests\Reservation\ReservationReserveRequest;
use App\Http\Requests\Reservation\SubscriberReservationHistoryRequest;
use App\Reporters\Reservation\BookReservationReporter;
use App\Reporters\Reservation\SubscriberReservationReporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReservationController extends ApiBaseController
{
    /**
     * @param ReservationServiceInterface $reservationService
     */
    public function __construct(private ReservationServiceInterface $reservationService)
    {
        //
    }

    public function reserve(ReservationReserveRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = DefaultReservationSchema::fromRequest($request);
            $this->reservationService->reserve($data);
            DB::commit();

            return $this->succeeded(message: __('messages.reservation.reserve.succeeded'));
        } catch (BookIsReservedException $e) {
            DB::rollBack();

            return $this->failed(__('messages.reservation.book.is_reserved'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->failed(__('messages.reservation.reserve.failed'));
        }
    }

    /**
     * Make a book as accessible.
     * 
     * @param ReservationFreeRequest $request
     * @return JsonResponse
     */
    public function free(ReservationFreeRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $this->reservationService->free($request->book_id);
            DB::commit();

            return $this->succeeded(message: __('messages.reservation.free.succeeded'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->failed(__('messages.reservation.free.failed'));
        }
    }

    /**
     * Generate a reservation history for a subscriber.
     * 
     * @param SubscriberReservationHistoryRequest $request
     * @return JsonResponse
     */
    public function history(SubscriberReservationHistoryRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            // TODO: Should be auto-resolved from container.
            $reporter = resolve(SubscriberReservationReporter::class);

            $history = $this->reservationService->history(
                $request->subscriber_id,
                $reporter
            );

            DB::commit();

            return $this->succeeded($history, __('messages.reservation.history.succeeded'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->failed(__('messages.reservation.history.failed'));
        }
    }

    /**
     * Generate a reservation report for a book.
     * 
     * @param BookReservationHistoryRequest $request
     * @return JsonResponse
     */
    public function book(BookReservationHistoryRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $reporter = resolve(BookReservationReporter::class);

            $report = $this->reservationService->book(
                $request->book_id,
                $reporter
            );

            DB::commit();

            return $this->succeeded($report, __('messages.reservation.report.succeeded'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->failed(__('messages.reservation.report.failed'));
        }
    }
}
