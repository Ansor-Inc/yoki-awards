<?php

use Modules\Book\Database\factories\BookFactory;
use Modules\Purchase\Database\factories\PurchaseFactory;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Purchase\Interfaces\PurchaseRepositoryInterface;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    public function test_user_can_create_purchase_for_paid_book()
    {
        $purchaseRepository = app(PurchaseRepositoryInterface::class);

        $user = $this->signIn();
        $book = BookFactory::new()->create(['is_free' => false]);

        $response = $this->actingAs($user)
            ->postJson("/api/books/{$book->id}/make-purchase", ["phone" => $user->phone])
            ->assertOk()
            ->assertValid();

        $purchase = $purchaseRepository->getPurchaseById($response['purchase']['id']);

        $this->assertEquals($purchase->state, PurchaseStatus::PENDING_PAYMENT->value);
        $this->assertEquals($purchase->from_balance, 0);

        $this->assertFalse($book->isBoughtBy($user));
    }

    public function test_user_can_purchase_free_book()
    {
        $user = $this->signIn();
        $book = BookFactory::new()->create(['is_free' => true]);

        $response = $this->postJson("/api/books/{$book->id}/make-purchase", [
            "phone" => $user->phone
        ])->assertOk()
            ->assertValid();

        $this->assertEquals($response['purchase']['state'], PurchaseStatus::COMPLETED->value);
        $this->assertTrue($book->isBoughtBy($user));
    }

    public function test_user_can_complete_purchase_from_balance()
    {
        $user = $this->signIn();
        $book = BookFactory::new()->create(['is_free' => false]);

        $purchase = PurchaseFactory::new()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'amount' => $book->price,
            'state' => PurchaseStatus::PENDING_PAYMENT->value
        ]);

        $user->deposit($purchase->amount + 10);

        $this->postJson("/api/purchases/{$purchase->id}/complete", [
            "from_balance" => $purchase->amount
        ])->assertOk()
            ->assertJson(['completed' => true]);

        $this->assertTrue($book->isBoughtBy($user));
        $this->assertEquals($user->getBalance(), 10);
    }

    public function test_user_cannot_complete_purchase_from_balance_if_amount_does_not_equals_to_from_balance()
    {
        $user = $this->signIn();
        $book = BookFactory::new()->create(['is_free' => false]);

        $purchase = PurchaseFactory::new()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'amount' => $book->price,
            'state' => PurchaseStatus::PENDING_PAYMENT->value
        ]);

        $user->deposit($purchase->amount + 10);

        $this->postJson("/api/purchases/{$purchase->id}/complete", [
            "from_balance" => $purchase->amount - 100
        ])->assertStatus(403)
            ->assertJson(['completed' => false]);

        $this->assertFalse($book->isBoughtBy($user));
        $this->assertEquals($user->getBalance(), $purchase->amount + 10);
    }

    public function test_user_cannot_complete_purchase_if_user_does_not_have_enough_balance()
    {
        $user = $this->signIn();
        $book = BookFactory::new()->create(['is_free' => false]);

        $purchase = PurchaseFactory::new()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'amount' => $book->price,
            'state' => PurchaseStatus::PENDING_PAYMENT->value
        ]);

        $user->deposit($purchase->amount - 10);

        $this->postJson("/api/purchases/{$purchase->id}/complete", [
            "from_balance" => $purchase->amount - 100
        ])->assertStatus(403)
            ->assertJson(['completed' => false]);

        $this->assertFalse($book->isBoughtBy($user));
        $this->assertEquals($user->getBalance(), $purchase->amount - 10);
    }
}
