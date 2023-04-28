<?php

namespace Modules\Comment\Repositories;

use Modules\Comment\Entities\Comment;
use Modules\Comment\Interfaces\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface
{

    public function getComments(array $filters)
    {
        $query = Comment::query()->filter($filters);

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->get();
    }

    public function storeComment(array $payload)
    {
        return Comment::query()->create($payload);
    }

    public function updateComment(Comment $comment, array $payload): bool
    {
        return $comment->update($payload);
    }

    public function deleteComment(Comment $comment): ?bool
    {
        return $comment->delete();
    }
}
