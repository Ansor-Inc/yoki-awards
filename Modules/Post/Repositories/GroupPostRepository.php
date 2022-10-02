<?php

namespace Modules\Post\Repositories;

use App\Models\Group;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Post\Repositories\Interfaces\GroupPostRepositoryInterface;

class GroupPostRepository implements GroupPostRepositoryInterface
{
    public function getGroupPosts(Group $group, array $filters)
    {
        $query = $group->posts()
            ->with(['author:id,fullname', 'group:id,owner_id'])
            ->withCount(['likes', 'comments'])
            ->applyCurrentUserDegreeScopeFilter();

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->get();
    }

    public function createPost(Group $group, array $payload): Model
    {
        return $group->posts()->create($payload);
    }

    public function updatePost(Post $post, array $payload): bool
    {
        return $post->update($payload);
    }

    public function deletePost(Post $post): ?bool
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