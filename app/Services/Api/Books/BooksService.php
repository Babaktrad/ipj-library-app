<?php

namespace App\Services\Api\Books;

use App\Enums\BookStatus;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\EntitySchema;
use App\Contracts\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

class BooksService extends BaseService
{
    /**
     * Get all books.
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return parent::all();
    }

    /**
     * Create and store a new book.
     * 
     * @param EntitySchema $request
     * @return Model
     */
    public function store(EntitySchema $request): Model
    {
        return $this->model->create([
            'title' => $request->title,
            'author' => $request->author,
            'published_at' => $request->published_at,
            'status' => $request->status
        ]);
    }

    /**
     * Update a book.
     * 
     * @param EntitySchema $request
     * @return bool
     */
    public function update(EntitySchema $request): bool
    {
        $found = $this->findById($request->id);

        return $found->update(Arr::except($request->toArray(), ['id']));
    }

    /**
     * Change the status of a book.
     * 
     * @param string|Model $book
     * @param BookStatus $status
     * @return void
     */
    public function changeStatus(string|Model $book, BookStatus $status): void
    {
        if ($book instanceof Model) {
            $book->status = $status;
            $book->save();
        } else {
            $book = $this->findById($book);
            $book->status = $status;
            $book->save();
        }
    }

    /**
     * Get the status of a book.
     * 
     * @param string|Model $book
     * @return BookStatus
     */
    public function getStatus(string|Model $book): BookStatus
    {
        if ($book instanceof Model) {
            return $book->status;
        } else {
            $book = $this->findById($book);
            return $book->status;
        }
    }

    /**
     * Check if a book is reserved.
     * 
     * @param string|Model $book
     * @return bool
     */
    public function isReserved(string|Model $book): bool
    {
        return $this->getStatus($book) === BookStatus::RESERVED;
    }
}