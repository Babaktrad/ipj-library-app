<?php

namespace App\Contracts\Books;

use App\Contracts\EntitySchema;
use \Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Enums\BookStatus;

class DefaultBookSchema extends EntitySchema
{
    /**
     * Create an instance of book schema.
     * 
     * @param ?string $id
     * @param string $title
     * @param string $author
     * @param Carbon $published_at
     * @param BookStatus $status
     */
    private function __construct(
        public readonly string $title,
        public readonly string $author,
        public readonly Carbon $published_at,
        public readonly ?string $id,
        public readonly BookStatus $status
    ) {
        //
    }

    /**
     * Create an instance of book schema from arguments.
     * 
     * @param null|string $id
     * @param string $title
     * @param string $author
     * @param null|string $published_at
     * @param BookStatus $status
     * @return DefaultBookSchema
     */
    public static function create(
        string $title,
        string $author,
        string $published_at,
        ?string $id = null,
        BookStatus $status = BookStatus::ACCESSIBLE
    ): self {
        $published_at = $published_at ? Carbon::parse($published_at) : Carbon::now();

        return new self(
            $title ?? "",
            $author ?? "",
            $published_at,
            $id,
            $status
        );
    }

    /**
     * Create an instance of book schema from request.
     * 
     * @param Request $request
     * @return DefaultBookSchema
     */
    public static function fromRequest(Request $request): self
    {
        $published_at = $request->input('published_at') ?
            Carbon::parse((string) $request->input('published_at')) : Carbon::now();

        $status = $request->input('status') instanceof BookStatus ?
            $request->input('status') : BookStatus::tryFrom((int) $request->input('status'));

        return new self(
            $request->input('title'),
            $request->input('author'),
            $published_at,
            $request->input('id'),
            $status
        );
    }

    /**
     * Convert the book schema to array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'published_at' => $this->published_at,
            'status' => $this->status,
        ];
    }
}