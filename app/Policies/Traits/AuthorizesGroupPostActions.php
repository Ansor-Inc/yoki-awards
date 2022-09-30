<?php

namespace App\Policies\Traits;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

trait AuthorizesGroupPostActions
{
    public function seePosts(User $user, Group $group)
    {
        return $group->currentUserPermissions['can_see_post'];
    }

    public function createPost(User $user, Group $group)
    {
        return $group->currentUserPermissions['can_create_post'];
    }

    public function updatePost(User $user, Group $group, Post $post)
    {
        return (int)$post->user_id === (int)$user->id;
    }

    public function deletePost(User $user, Post $post)
    {
        return (int)$post->user_id === (int)$user->id;
    }
}