<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserPermissionsTest extends TestCase
{
    public function test_user_can_retrieve_his_roles(): void
    {
        $this->signIn();
        $this->getJson('/api/me')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'roles' => []
                ]
            ]);
    }
}
