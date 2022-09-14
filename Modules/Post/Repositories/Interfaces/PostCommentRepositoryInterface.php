<?php

namespace Modules\Post\Repositories\Interfaces;

use App\Models\Comment;
use App\Models\Post;

interface PostCommentRepositoryInterface
{
    public function getPostComments(Post $post, array $filters);

    public function createPostComment(Post $post, array $payload);

    public function updatePostComment(Comment $comment, array $payload);

    public function deletePostComment(Comment $comment);
}