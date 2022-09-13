<?php

namespace Modules\Post\Repositories\Interfaces;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;

interface GroupPostRepositoryInterface
{
    public function getGroupPosts(Group $group, array $filters);

    public function createPost(Group $group, array $payload);

    public function updatePost(Post $post, array $payload);

    public function deletePost(Post $post);

    public function togglePostLike(Post $post, User $user);
}