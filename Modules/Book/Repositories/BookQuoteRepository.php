<?php

namespace Modules\Book\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Book\Entities\Book;
use Modules\Book\Entities\Quote;
use Modules\Book\Interfaces\BookQuoteRepositoryInterface;

class BookQuoteRepository implements BookQuoteRepositoryInterface
{

    public function getBookQuotes(int $bookId)
    {
        return Quote::query()
            ->where('book_id', $bookId)
            ->with('user:id,avatar,fullname')
            ->latest()
            ->get();
    }

    public function createQuote(int $bookId, array $payload)
    {
        if (!Book::query()->where('id', $bookId)->exists()) {
            throw new ModelNotFoundException('Book with this id not found!');
        }

        return Quote::query()->create(array_merge(
            $payload,
            ['book_id' => $bookId]
        ));
    }

    public function updateQuote(int $quoteId, array $payload)
    {
        if (!Quote::query()->where('id', $quoteId)->exists()) {
            throw new ModelNotFoundException('Quote with this id not found!');
        }

        return Quote::query()->where('id', $quoteId)->update($payload);
    }

    public function getQuotes(array $filters)
    {
        return Quote::query()->with('book:id')->filter($filters)->get();
    }
}
