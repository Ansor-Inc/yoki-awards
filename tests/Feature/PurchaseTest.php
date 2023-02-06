<?php

use Modules\Book\Database\factories\BookFactory;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\User\Database\factories\UserFactory;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    public function test_user_can_purchase_paid_book()
    {
        $user = $this->createUser();
        $book = BookFactory::new()->create(['is_free' => false]);

        $response = $this->actingAs($user)
            ->post("/api/books/{$book->id}/make-purchase", [
                "phone" => $user->phone
            ]);

        $response->assertOk()
            ->assertValid();

        $this->assertEquals($response['purchase']['state'], PurchaseStatus::PENDING_PAYMENT->value);
    }

    public function test_user_can_purchase_free_book()
    {
        $user = $this->createUser();
        $book = BookFactory::new()->create(['is_free' => true]);

        $response = $this->actingAs($user)
            ->post("/api/books/{$book->id}/make-purchase", [
                "phone" => $user->phone
            ]);

        $response->assertOk()
            ->assertValid();

        $this->assertEquals($response['purchase']['state'], PurchaseStatus::COMPLETED->value);
        $this->assertTrue($book->isBoughtBy($user));
    }

    private function createUser()
    {
        return UserFactory::new()->create();
    }
}
