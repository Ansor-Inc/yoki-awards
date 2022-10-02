<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Post $post): Response|bool
    {
        return $this->isOwner($user, $post);
    }

    public function delete(User $user, Post $post): Response|bool
    {
        return $this->isOwner($user, $post);
    }

    protected function isOwner(User $user, Post $post): Response|bool
    {
        return (int)$user->id === (int)$post->user_id ? true : Response::deny('You are not the owner of this post!');
    }
}
