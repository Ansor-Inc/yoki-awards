<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function createPostComment(User $user, ?Comment $comment, Post $post)
    {
        return $post->group->currentUserPermissions['can_comment'];
    }

    public function update(User $user, Comment $comment)
    {
        return (int)$user->id === (int)$comment->user_id;
    }

    public function delete(User $user, Comment $comment)
    {
        return (int)$user->id === (int)$comment->user_id;
    }

}
