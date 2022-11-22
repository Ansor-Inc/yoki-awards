<?php

namespace Modules\Purchase\Repositories\Interfaces;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Book\Entities\Book;
use Modules\User\Entities\User;

interface PurchaseRepositoryInterface
{
    public function getPurchaseHistory(Authenticatable|User $user, $perPage);

    public function getPurchasedBooks(Authenticatable|User $user, array $filters);

    public function makePurchase(Authenticatable|User $user, Book $book, string $phone);
}