<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            "fullname" => "John",
            "phone" => 998991234567,
            "password" => '12345678a',
            "password_confirmation" => '12345678a'
        ])
            ->assertOk()
            ->assertValid()
            ->assertJsonStructure(['token_type', 'token']);

        $this->withToken($response['token'])
            ->getJson('/api/me')
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'fullname' => "John",
            "phone" => 998991234567,
            "verified" => false
        ]);
    }
}
