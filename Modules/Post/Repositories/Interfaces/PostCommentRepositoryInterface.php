<?php

namespace Modules\Post\Repositories\Interfaces;

use Modules\Comment\Entities\Comment;
use Modules\Post\Entities\Post;

interface PostCommentRepositoryInterface
{
    public function getPostComments(Post $post, array $filters);

    public function createPostComment(Post $post, array $payload);

    public function updatePostComment(Comment $comment, array $payload);

    public function deletePostComment(Comment $comment);

    public function complain(Comment $comment, string $body);
}
