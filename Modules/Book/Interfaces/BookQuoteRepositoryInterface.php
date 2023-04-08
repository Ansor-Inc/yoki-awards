<?php

namespace Modules\Book\Interfaces;

interface BookQuoteRepositoryInterface
{
    public function getQuotes(array $filters);

    public function getBookQuotes(int $bookId);

    public function createQuote(int $bookId, array $payload);

    public function updateQuote(int $quoteId, array $payload);
}
