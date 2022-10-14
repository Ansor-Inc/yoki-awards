<?php

namespace App\Policies;

use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Group\Entities\Post;
use Modules\User\Entities\User;

class CommentPolicy
{
    use HandlesAuthorization;

    public function createPostComment(User $user, Post $post)
    {
        return $post->group->currentUserPermissions['can_comment'];
    }

    public function update(User $user, Comment $comment)
    {
        return $this->isOwner($user, $comment);
    }

    public function delete(User $user, Comment $comment)
    {
        return $this->isOwner($user, $comment);
    }

    protected function isOwner(User $user, Comment $comment)
    {
        return (int)$user->id === (int)$comment->user_id;
    }
}
