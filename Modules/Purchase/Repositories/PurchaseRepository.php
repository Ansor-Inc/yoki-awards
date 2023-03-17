<?php

namespace Modules\Purchase\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Book\Entities\Book;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Purchase\Interfaces\PurchaseRepositoryInterface;
use Modules\User\Entities\User;

class PurchaseRepository implements PurchaseRepositoryInterface
{
    public function getPurchaseHistory(User|Authenticatable $user, $perPage)
    {
        $query = $user->purchases()->with('book')->latest();

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function getPurchaseById(int $id)
    {
        return Purchase::query()->find($id);
    }

    public function getPurchasedBooks(User|Authenticatable $user, array $filters)
    {
        $query = Book::query()
            ->select(['books.id', 'books.title', 'books.author_id', 'books.is_free', 'books.book_type', 'books.price'])
            ->withAvg('ratings as rating', 'rating')
            ->with('author:id,firstname,lastname')
            ->join('purchases', 'purchases.book_id', '=', 'books.id')
            ->where('purchases.state', PurchaseStatus::COMPLETED->value)
            ->where('purchases.user_id', $user->id);

        if (isset($filters['type'])) {
            $query->where('books.book_type', $filters['type']);
        }

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->get();
    }

    public function makePurchase(User|Authenticatable $user, Book $book, string $phone)
    {
        return Purchase::query()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'amount' => $book->is_free ? 0 : $book->price,
            'phone' => $phone,
            'state' => $book->is_free ? PurchaseStatus::COMPLETED->value : PurchaseStatus::PENDING_PAYMENT->value,
            'user_data' => $user,
            'book_data' => $book->load('publisher')->append('image')
        ]);
    }
}
