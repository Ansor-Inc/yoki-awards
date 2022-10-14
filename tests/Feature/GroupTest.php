<?php

namespace Tests\Feature;

use Modules\Group\Entities\Group;
use Modules\User\Entities\User;
use Tests\TestCase;

class GroupTest extends TestCase
{
    //use RefreshDatabase;
//    use WithoutMiddleware;

    protected $user;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_group()
    {

        $data = Group::factory()->make()->toArray();

        $this->authenticate()
            ->postJson('/api/groups', $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('groups', $data);
    }

    public function test_user_can_join_group()
    {
        $group = Group::factory()->create();

        $response = $this->authenticate()
            ->postJson("/api/groups/{$group->id}/join")
        ->assertStatus(200);

        $this->assertDatabaseHas('memberships', [
            'user_id' => $this->user->id,
            'group_id' => $group->id,
            'approved' => !$group->is_private
        ]);
    }

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->user = $user;
        $this->actingAs($user);
        return $this;
    }

}
