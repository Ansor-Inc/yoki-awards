<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountTest extends TestCase
{
    public function test_user_get_account_info()
    {
        $this->signIn();
        $this->getJson('/api/me')
            ->assertOk();
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = $this->signIn();
        $this->deleteJson('/api/me', ['password' => 'test'])
            ->assertStatus(422);

        $this->deleteJson('/api/me', ['password' => 'password'])
            ->assertStatus(200);

        $this->assertEquals(0, $user->tokens()->count());
        $this->assertNull($user->fresh());
    }
}
