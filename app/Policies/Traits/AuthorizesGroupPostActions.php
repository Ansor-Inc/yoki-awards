<?php

namespace App\Policies\Traits;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;

trait AuthorizesGroupPostActions
{
    public function seePosts()
    {
        return true;
    }

    public function createPost(User $user, Group $group)
    {
        return true;
        //if owner
        //if admin and have permission
        //is not in blacklist
    }

    public function updatePost(User $user, Group $group, Post $post)
    {
        return true;
    }

    public function deletePost(User $user, Group $group, Post $post)
    {
        return true;
    }
}