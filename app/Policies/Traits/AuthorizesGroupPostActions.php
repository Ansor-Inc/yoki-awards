<?php

namespace App\Policies\Traits;

use App\Models\Group;
use App\Models\User;

trait AuthorizesGroupPostActions
{
    public function getPosts(User $user, Group $group)
    {
        return $group->currentUserPermissions['can_see_post'];
    }

    public function createPost(User $user, Group $group)
    {
        return $group->currentUserPermissions['can_create_post'];
    }
}