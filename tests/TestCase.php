<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\User\Database\factories\UserFactory;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function signIn()
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user, 'sanctum');

        return $user;
    }

}
