<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Modules\Book\Entities\Book;
use Modules\Book\Transformers\BookListingResource;
use Modules\Book\Transformers\PurchaseResource;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Purchase\Http\Requests\GetCompletedPurchasesRequest;
use Modules\Purchase\Http\Requests\MakePurchaseRequest;
use Modules\Purchase\Interfaces\PurchaseRepositoryInterface;

class BookPurchaseController extends Controller
{
    public function __construct(protected PurchaseRepositoryInterface $purchaseRepository)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $purchases = $this->purchaseRepository->getPurchaseHistory($request->user(), $request->input('per_page'));

        return PurchaseResource::collection($purchases);
    }

    public function getCompletedPurchases(GetCompletedPurchasesRequest $request): AnonymousResourceCollection
    {
        $purchasedBooks = $this->purchaseRepository->getPurchasedBooks($request->user(), $request->validated());

        return BookListingResource::collection($purchasedBooks);
    }

    public function makePurchase(Book $book, MakePurchaseRequest $request): Response|Application|ResponseFactory
    {
        $user = auth()->user();

        //If user has already bought book
        if ($purchase = $user->purchases()->completed()->ofBook($book)->first()) {
            return $this->userHasAlreadyBoughtResponse($purchase);
        }

        //If user made purchase but did not pay
        if ($purchase = $user->purchases()->pending()->ofBook($book)->first()) {
            return $this->pendingPaymentResponse($purchase);
        }

        //Create new purchase
        try {
            $purchase = $this->purchaseRepository->makePurchase($user, $book, $request->input('phone'));
        } catch (\Throwable $exception) {
            report($exception);
            return response(['message' => $exception->getMessage()], 500);
        }

        //If book is free purchase status sets to COMPLETED
        if ($purchase->state === PurchaseStatus::COMPLETED->value) {
            return $this->hasBoughtResponse($purchase);
        }

        return $this->pendingPaymentResponse($purchase);
    }

    protected function userHasAlreadyBoughtResponse($purchase): Response|Application|ResponseFactory
    {
        return $this->purchaseResponse($purchase, 'Siz allaqachon bu kitobni sotib olgansiz!');
    }

    protected function pendingPaymentResponse($purchase): Response|Application|ResponseFactory
    {
        return $this->purchaseResponse($purchase, "To'lov kutilmoqda!");
    }

    protected function hasBoughtResponse(Model|Builder $purchase): Response|Application|ResponseFactory
    {
        return $this->purchaseResponse($purchase, 'Kitob sotib olindi!');
    }

    protected function purchaseResponse($purchase, string $message): Response|Application|ResponseFactory
    {
        return response([
            'message' => $message,
            'purchase' => PurchaseResource::make($purchase->load('book', 'book.publisher', 'book.author'))
        ]);
    }
}
