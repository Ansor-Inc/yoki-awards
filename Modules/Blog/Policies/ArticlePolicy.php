<?php

namespace Modules\Blog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Blog\Entities\Article;
use Modules\User\Entities\User;
use Modules\User\Enums\UserRole;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::BLOGGER->value)
            || $user->hasRole(UserRole::APPROVED_BLOGGER->value);
    }

    public function own(User $user, Article $article): bool
    {
        return $user->id === $article->user_id
            && $user->getMorphClass() === $article->user_type;
    }
}
