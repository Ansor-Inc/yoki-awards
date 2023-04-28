<?php

namespace Modules\Reaction\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Reaction\Entities\Like;

trait Liker
{
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'user_id', $this->getKeyName())
            ->where('disliked', false);
    }

    public function like(Model $object)
    {
        return Like::query()->updateOrCreate([
            'likeable_type' => $object->getMorphClass(),
            'likeable_id' => $object->getKey(),
            'user_id' => $this->getKey()
        ], ['disliked' => false]);
    }

    /**
     * @throws \Exception
     */
    public function unlike(Model $object): bool
    {
        return Like::query()
            ->where('likeable_id', $object->getKey())
            ->where('likeable_type', $object->getMorphClass())
            ->where('user_id', $this->getKey())
            ->where('disliked', false)
            ->delete();
    }

    /**
     * @throws \Exception
     */
    public function toggleLike(Model $object)
    {
        return $this->hasLiked($object) ? $this->unlike($object) : $this->like($object);
    }

    public function hasLiked(Model $object): bool
    {
        return ($this->relationLoaded('likes') ? $this->likes : $this->likes())
                ->where('likeable_id', $object->getKey())
                ->where('likeable_type', $object->getMorphClass())
                ->count() > 0;
    }
}
