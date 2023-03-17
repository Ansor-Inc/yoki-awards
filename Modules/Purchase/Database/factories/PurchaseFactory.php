<?php

namespace Modules\Purchase\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Book\Database\factories\BookFactory;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\User\Database\factories\UserFactory;

class PurchaseFactory extends Factory
{
    protected $model = \Modules\Purchase\Entities\Purchase::class;

    public function definition(): array
    {
        $book = BookFactory::new()->create();
        $user = UserFactory::new()->create();
        return [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'amount' => $book->price,
            'phone' => $user->phone,
            'state' => PurchaseStatus::PENDING_PAYMENT->value,
            'user_data' => $user->toJson(),
            'book_data' => $book->toJson(),
            'from_balance' => 0
        ];
    }
}

