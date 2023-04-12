<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContentTest extends TestCase
{
    public function test_user_can_retrieve_banners()
    {
        $this->get('/api/content/banners')
            ->assertOk();
    }

    public function test_user_can_retrieve_popup()
    {
        $this->get('/api/content/popup')
            ->assertOk();
    }
}
