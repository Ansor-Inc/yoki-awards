<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Modules\Comment\Entities\Comment;
use Modules\Post\Entities\Post;
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

    public function complain(User $user, Comment $comment)
    {
        return $user->complaints()->where(['complainable_type' => 'comment', 'complainable_id' => $comment->id])->exists() ?
            Response::deny('You have already complained about this comment!') :
            Response::allow();
    }

    protected function isOwner(User $user, Comment $comment)
    {
        return (int)$user->id === (int)$comment->user_id;
    }

}
