<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Modules\Book\Entities\Book;
use Modules\Book\Transformers\BookListingResource;
use Modules\Book\Transformers\PurchaseResource;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Purchase\Http\Requests\GetCompletedPurchasesRequest;
use Modules\Purchase\Http\Requests\MakePurchaseRequest;
use Modules\Purchase\Repositories\Interfaces\PurchaseRepositoryInterface;

class BookPurchaseController extends Controller
{
    protected PurchaseRepositoryInterface $purchaseRepository;

    public function __construct(PurchaseRepositoryInterface $repository)
    {
        $this->purchaseRepository = $repository;
    }

    public function index(Request $request)
    {
        $purchases = $this->purchaseRepository->getPurchaseHistory($request->user(), $request->input('per_page'));

        return PurchaseResource::collection($purchases);
    }

    public function getCompletedPurchases(GetCompletedPurchasesRequest $request)
    {
        $purchasedBooks = $this->purchaseRepository->getPurchasedBooks($request->user(), $request->validated());

        return BookListingResource::collection($purchasedBooks);
    }

    public function makePurchase(Book $book, MakePurchaseRequest $request)
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
            return response(['message' => $exception->getMessage()], 500);
        }

        //If book is free purchase status sets to COMPLETED
        if ($purchase->state === PurchaseStatus::COMPLETED->value) {
            return $this->hasBoughtResponse($purchase);
        }

        return $this->pendingPaymentResponse($purchase);
    }

    protected function userHasAlreadyBoughtResponse($purchase)
    {
        return $this->purchaseResponse($purchase, 'Siz allaqachon bu kitobni sotib olgansiz!');
    }

    protected function pendingPaymentResponse($purchase)
    {
        return $this->purchaseResponse($purchase, "To'lov kutilmoqda!");
    }

    protected function hasBoughtResponse(Model|Builder $purchase)
    {
        return $this->purchaseResponse($purchase, 'Kitob sotib olindi!');
    }

    protected function purchaseResponse($purchase, string $message)
    {
        return response([
            'message' => $message,
            'purchase' => $purchase->load('book', 'book.publisher', 'book.author')
        ]);
    }

}