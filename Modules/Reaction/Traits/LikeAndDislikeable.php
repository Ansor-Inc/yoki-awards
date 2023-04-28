<?php

namespace Modules\Reaction\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Reaction\Entities\Like;

trait LikeAndDislikeable
{
    public function userLike(): MorphOne
    {
        return $this->votes()->one()->where('user_id', auth()->id());
    }

    public function votes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function likes()
    {
        return $this->votes()->where('disliked', false);
    }

    public function dislikes()
    {
        return $this->votes()->where('disliked', true);
    }

    public function isLikedBy(Authenticatable $user)
    {
        return ($this->relationLoaded('likes') ? $this->likes : $this->likes())
            ->where('user_id', $user->getKey())
            ->exists();
    }

    public function isDisLikedBy(Authenticatable $user)
    {
        return ($this->relationLoaded('dislikes') ? $this->dislikes : $this->dislikes())
            ->where('user_id', $user->getKey())
            ->exists();
    }

}
