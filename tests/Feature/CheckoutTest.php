<?php

namespace Tests\Feature;

use Modules\Book\Database\Factories\BookFactory;
use Modules\Purchase\Database\factories\PurchaseFactory;
use Modules\Purchase\Enums\PurchaseStatus;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    public function test_user_can_checkout_for_purchase()
    {
        $user = $this->signIn();
        $book = BookFactory::new()->create(['is_free' => false]);

        $purchase = $this->createPurchase($user, $book, PurchaseStatus::PENDING_PAYMENT->value);

        $this->postJson("/api/purchases/{$purchase->id}/checkout", [
            "payment_system" => "payme"
        ])->assertOk()
            ->assertValid()
            ->assertJsonStructure(["checkout_link"]);
    }

    public function test_user_cannot_checkout_for_completed_or_canceled_purchase()
    {
        $user = $this->signIn();
        $book = BookFactory::new()->create(['is_free' => false]);
        $purchase = $this->createPurchase($user, $book, PurchaseStatus::COMPLETED->value);

        $this->postJson("/api/purchases/{$purchase->id}/checkout", ["payment_system" => "payme"])
            ->assertStatus(403);

        $purchase = $this->createPurchase($user, $book, PurchaseStatus::CANCELED->value);

        $this->postJson("/api/purchases/{$purchase->id}/checkout", ["payment_system" => "payme"])
            ->assertStatus(403);
    }

    public function test_user_can_checkout_from_balance()
    {
        $user = $this->signIn();
        $book = BookFactory::new()->create(['is_free' => false]);

        $purchase = $this->createPurchase($user, $book, PurchaseStatus::PENDING_PAYMENT->value);
        $fromBalance = $book->price - 1000;

        $user->deposit($fromBalance + 10);

        $this->postJson("/api/purchases/{$purchase->id}/checkout", ["payment_system" => "payme", "from_balance" => $fromBalance])
            ->assertOk()
            ->assertValid()
            ->assertJsonStructure(["checkout_link"]);
    }

    public function test_user_cannot_checkout_from_balance_if_book_price_is_lower_than_from_balance()
    {
        $user = $this->signIn();
        $book = BookFactory::new()->create(['is_free' => false]);
        $purchase = $this->createPurchase($user, $book, PurchaseStatus::PENDING_PAYMENT->value);

        $fromBalance = $book->price + 1000;
        $user->deposit($fromBalance + 10);

        $this->postJson("/api/purchases/{$purchase->id}/checkout", [
            "payment_system" => "payme",
            "from_balance" => $fromBalance
        ])->assertStatus(403);
    }

    public function test_user_cannot_checkout_from_balance_if_user_does_not_have_enough_balance()
    {
        $user = $this->signIn();
        $book = BookFactory::new()->create(['is_free' => false]);
        $purchase = $this->createPurchase($user, $book, PurchaseStatus::PENDING_PAYMENT->value);

        $fromBalance = $book->price - 1000;
        $user->deposit($fromBalance - 1);

        $this->postJson("/api/purchases/{$purchase->id}/checkout", [
            "payment_system" => "payme",
            "from_balance" => $fromBalance
        ])->assertStatus(403);
    }

    public function test_user_cannot_checkout_if_amount_equals_to_from_balance()
    {
        $user = $this->signIn();
        $book = BookFactory::new()->create(['is_free' => false]);
        $purchase = $this->createPurchase($user, $book, PurchaseStatus::PENDING_PAYMENT->value);

        $user->deposit($book->price);

        $this->postJson("/api/purchases/{$purchase->id}/checkout", [
            "payment_system" => "payme",
            "from_balance" => $purchase->amount
        ])->assertStatus(403);

        $this->assertFalse($book->isBoughtBy($user));
    }

    private function createPurchase($user, $book, string $state)
    {
        return PurchaseFactory::new()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'amount' => $book->price,
            'user_data' => $user->toJson(),
            'book_data' => $book->toJson(),
            'state' => $state
        ]);
    }

}
