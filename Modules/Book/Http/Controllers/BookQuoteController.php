<?php

namespace Modules\Book\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Book\Http\Requests\GetQuotesRequest;
use Modules\Book\Http\Requests\StoreQuoteRequest;
use Modules\Book\Interfaces\BookQuoteRepositoryInterface;
use Modules\Book\Transformers\QuoteResource;

class BookQuoteController extends Controller
{
    public function __construct(protected BookQuoteRepositoryInterface $bookQuoteRepository)
    {
    }

    public function index($bookId)
    {
        return QuoteResource::collection($this->bookQuoteRepository->getBookQuotes((int)$bookId));
    }

    public function getAllQuotes(GetQuotesRequest $request)
    {
        return QuoteResource::collection($this->bookQuoteRepository->getQuotes($request->validated()));
    }

    public function store($bookId, StoreQuoteRequest $request)
    {
        $quote = $this->bookQuoteRepository->createQuote((int)$bookId, array_merge($request->validated(), [
            'user_id' => auth()->id()
        ]));

        return QuoteResource::make($quote);
    }

    public function update($quoteId, StoreQuoteRequest $request)
    {
        $affectedRows = $this->bookQuoteRepository->updateQuote((int)$quoteId, $request->validated());

        return $affectedRows > 0 ? response(['message' => 'Updated!']) : $this->failed();
    }
}
