<?php

namespace Modules\Post\Repositories\Interfaces;

use Modules\Group\Entities\Group;
use Modules\Post\Entities\Post;
use Modules\User\Entities\User;

interface GroupPostRepositoryInterface
{
    public function getGroupPosts(Group $group, array $filters);

    public function getActualPosts(int $limit = null);

    public function createPost(Group $group, array $payload);

    public function updatePost(Post $post, array $payload);

    public function deletePost(Post $post);

    public function togglePostLike(Post $post, User $user);
}
