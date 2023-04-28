<?php

namespace Modules\Comment\Interfaces;

use Modules\Comment\Entities\Comment;

interface CommentRepositoryInterface
{
    public function getComments(array $filters);

    public function storeComment(array $payload);

    public function updateComment(Comment $comment, array $payload);

    public function deleteComment(Comment $comment);
}
