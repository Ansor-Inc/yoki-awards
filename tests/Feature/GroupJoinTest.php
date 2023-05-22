<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Group\Database\factories\GroupFactory;
use Tests\TestCase;

class GroupJoinTest extends TestCase
{
    public function test_user_can_join_public_group(): void
    {
        $user = $this->signIn();

        $group = GroupFactory::new()->create();

        $response = $this->postJson("/api/groups/{$group->id}/join")
            ->assertStatus(200);

        $response->assertJson(['joined' => true]);

        $this->assertTrue($group->hasMember($user));;

        $this->assertDatabaseHas('memberships', [
            'user_id' => $user->id,
            'group_id' => $group->id,
            'approved' => true
        ]);
    }

    public function test_user_can_request_to_join_private_group()
    {
        $user = $this->signIn();

        $group = GroupFactory::new()->create([
            'is_private' => true
        ]);

        $response = $this->postJson("/api/groups/{$group->id}/join")
            ->assertStatus(200);

        $response->assertJson(['joined' => false]);

        $this->assertFalse($group->hasMember($user));;

        $this->assertDatabaseHas('memberships', [
            'user_id' => $user->id,
            'group_id' => $group->id,
            'approved' => false
        ]);
    }

}
