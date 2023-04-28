<?php

namespace Modules\Post\Repositories;

use Modules\Comment\Entities\Comment;
use Modules\Post\Entities\Post;
use Modules\Post\Repositories\Interfaces\PostCommentRepositoryInterface;
use Modules\User\Entities\Complaint;

class PostCommentRepository implements PostCommentRepositoryInterface
{
    public function getPostComments(Post $post, array $filters)
    {
        $query = $post->comments()
            ->whereNull('reply_id')
            ->with(['descendants', 'user:id,fullname,avatar'])
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

    public function complain(Comment $comment, string $body)
    {
        return auth()->user()->complain(new Complaint([
            'complainable_type' => 'comment',
            'complainable_id' => $comment->id,
            'body' => $body
        ]));

    }
}
