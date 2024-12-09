<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Books\DefaultBookSchema;
use App\Contracts\Services\EntityServiceInterface;
use App\Enums\BookStatus;
use App\Http\Requests\Books\BookCreateRequest;
use App\Http\Requests\Books\BookUpdateRequest;
use App\Http\Requests\Books\BookDeleteRequest;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class BooksController extends ApiBaseController
{
    /**
     * @param EntityServiceInterface $bookService
     */
    public function __construct(private EntityServiceInterface $bookService)
    {
        //
    }

    /**
     * List all books.
     * 
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        try {
            $books = BookResource::collection($this->bookService->all());

            return $this->succeeded($books);
        } catch (\Throwable $th) {
            return $this->failed();
        }
    }

    /**
     * Create a new book.
     * 
     * @param BookCreateRequest $request
     * @return JsonResponse
     */
    public function store(BookCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $request->attributes->set('status', BookStatus::ACCESSIBLE);
            $data = DefaultBookSchema::fromRequest($request);
            $book = $this->bookService->store($data);
            $resource = new BookResource($book);

            DB::commit();

            return $this->succeeded($resource);
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->failed(__('messages.books.create.failed'));
        }
    }

    /**
     * Update a book.
     * 
     * @param BookUpdateRequest $request
     * @return JsonResponse
     */
    public function update(BookUpdateRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = DefaultBookSchema::fromRequest($request);
            $this->bookService->update($data);

            DB::commit();

            return $this->succeeded(message: __('messages.books.update.succeeded'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->failed(__('messages.books.update.failed'));
        }
    }

    /**
     * Delete a book.
     * 
     * @param BookDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(BookDeleteRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $this->bookService->delete($request->id);

            DB::commit();

            return $this->succeeded(message: __('messages.books.delete.succeeded'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->failed(__('messages.books.delete.failed'));
        }
    }
}
