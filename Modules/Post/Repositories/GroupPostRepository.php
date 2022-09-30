<?php

namespace Modules\Post\Repositories;

use App\Models\Group;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Modules\Post\Repositories\Interfaces\GroupPostRepositoryInterface;

class GroupPostRepository implements GroupPostRepositoryInterface
{

    public function getGroupPosts(Group $group, array $filters)
    {
        $query = $group->posts()
            ->filter($filters)
            ->applyCurrentUserDegreeScopeFilter();

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->get();
    }

    public function createPost(Group $group, array $payload)
    {
        return $group->posts()->create($payload);
    }

    public function updatePost(Post $post, array $payload)
    {
        return $post->update($payload);
    }

    public function deletePost(Post $post)
    {
        return $post->delete();
    }

    public function togglePostLike(Post $post, User $user)
    {
        $postLike = PostLike::query()->firstWhere(['user_id' => $user->id, 'post_id' => $post->id]);

        if ($postLike) {
            $postLike->update(['liked' => !$postLike->liked]);
        } else {
            $postLike = PostLike::query()->create(['user_id' => $user->id, 'post_id' => $post->id, 'liked' => true]);
        }

        return $postLike->liked;
    }
}