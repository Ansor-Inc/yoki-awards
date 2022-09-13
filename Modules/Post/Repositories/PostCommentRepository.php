<?php

namespace Modules\Post\Repositories;

use App\Models\Comment;
use App\Models\Post;
use Modules\Post\Repositories\Interfaces\PostCommentRepositoryInterface;

class PostCommentRepository implements PostCommentRepositoryInterface
{
    public function getPostComments(Post $post, array $filters)
    {
        return $post->comments;
    }

    public function createPostComment(Post $post, array $payload)
    {
        return $post->comments()->create(array_merge($payload, [
            'user_id' => auth('sanctum')->user()->id
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