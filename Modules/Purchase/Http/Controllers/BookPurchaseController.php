<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Modules\Book\Entities\Book;
use Modules\Book\Transformers\PurchaseResource;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Purchase\Http\Requests\MakePurchaseRequest;

class BookPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->purchases()->with('book')->latest();

        $purchases = $request->has('per_page') ? $query->paginate($request->input('per_page')) : $query->get();

        return PurchaseResource::collection($purchases);
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
            $purchase = $this->createPurchase($user, $book, $request->input('phone'));
        } catch (\Throwable $exception) {
            return response(['message' => $exception->getMessage()], 500);
        }

        //If book is free purchase status sets to COMPLETED
        if ($purchase->state === PurchaseStatus::COMPLETED->value) {
            return $this->hasBoughtResponse($purchase);
        }

        return $this->pendingPaymentResponse($purchase);
    }

    protected function createPurchase(Authenticatable $user, Book $book, string $phone): Model|Builder
    {
        return Purchase::query()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'amount' => $book->is_free ? 0 : $book->price,
            'phone' => $phone,
            'state' => $book->is_free ? PurchaseStatus::COMPLETED->value : PurchaseStatus::PENDING_PAYMENT->value,
            'user_data' => $user,
            'book_data' => $book->load('publisher')
        ]);
    }

    protected function userHasAlreadyBoughtResponse($purchase)
    {
        return response([
            'message' => 'Siz allaqachon bu kitobni sotib olgansiz!',
            'purchase' => PurchaseResource::make($purchase)
        ]);
    }

    protected function pendingPaymentResponse($purchase)
    {
        return response([
            'message' => "To'lov kutilmoqda!",
            'purchase' => PurchaseResource::make($purchase)
        ]);
    }

    protected function hasBoughtResponse(Model|Builder $purchase)
    {
        return response([
            'message' => 'Kitob sotib olindi!',
            'purchase' => PurchaseResource::make($purchase)
        ]);
    }

}