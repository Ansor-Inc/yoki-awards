<?php

namespace Modules\Post\Repositories;

use App\Models\Comment;
use App\Models\Post;
use Modules\Post\Repositories\Interfaces\PostCommentRepositoryInterface;

class PostCommentRepository implements PostCommentRepositoryInterface
{
    public function getPostComments(Post $post, array $filters)
    {
        $query = $post->comments()
            ->with('descendants')
            ->latest();

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->get();
    }

    public function createPostComment(Post $post, array $payload)
    {
        return $post->comments()->create(array_merge($payload, [
            'user_id' => auth()->id()
        ]));
    }

    public function updatePostComment(Comment $comment, array $payload)
    {
        return $comment->update($payload);
    }

    public function deletePostComment(Comment $comment)
    {
        return $comment->delete();
    }
}