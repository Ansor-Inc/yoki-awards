<?php

namespace Tests\Feature;

use Modules\Group\Database\factories\GroupCategoryFactory;
use Tests\TestCase;

class GroupTest extends TestCase
{
    public function test_user_can_create_group(): void
    {
        $user = $this->signIn();

        $payload = [
            'title' => 'group',
            'member_limit' => 3,
            'is_private' => false,
            'group_category_id' => GroupCategoryFactory::new()->create()->id,
            'degree_scope' => ['GENIUS']
        ];

        $response = $this->postJson('/api/groups', $payload);

        $response->assertStatus(200);

        $payload['degree_scope'] = json_encode($payload['degree_scope']);
        $this->assertDatabaseHas('groups', array_merge($payload, ['owner_id' => $user->id]));
    }
}
