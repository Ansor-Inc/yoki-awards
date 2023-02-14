<?php

namespace Tests\Feature;

use Modules\Purchase\Database\factories\CouponFactory;
use Tests\TestCase;

class CouponTest extends TestCase
{
    public function test_user_can_see_the_balance()
    {
        $this->signIn();

        $this->getJson('/api/me/balance')
            ->assertOk()
            ->assertExactJson([
                "balance" => 0
            ]);
    }

    public function test_users_can_fill_their_balance_using_coupons()
    {
        $user = $this->signIn();

        $coupon = CouponFactory::new()->create();

        $this->postJson('/api/coupons', ['code' => $coupon->code])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'balance' => (int)$coupon->amount
        ]);

        $this->assertDatabaseHas('coupon_uses', [
            'user_id' => $user->id,
            'coupon_id' => $coupon->id
        ]);

        $this->assertEquals($coupon->amount, $user->getBalance());
    }

    public function test_users_cannot_use_a_coupon_more_than_once()
    {
        $this->signIn();
        $coupon = CouponFactory::new()->create();

        $this->postJson('/api/coupons', ['code' => $coupon->code])->assertOk();
        $this->postJson('/api/coupons', ['code' => $coupon->code])->assertStatus(422);
    }

    public function test_users_cannot_use_expired_coupons()
    {
        $this->signIn();
        $coupon = CouponFactory::new()->create([
            'expires_at' => now()->subMinute()
        ]);

        $this->postJson('/api/coupons', ['code' => $coupon->code])->assertStatus(422);
    }
}
