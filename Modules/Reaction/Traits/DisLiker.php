<?php

namespace Modules\Reaction\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Reaction\Entities\Like;

trait DisLiker
{
    public function dislike(Model $object)
    {
        return Like::query()->updateOrCreate([
            'likeable_type' => $object->getMorphClass(),
            'likeable_id' => $object->getKey(),
            'user_id' => $this->getKey()
        ], ['disliked' => true]);
    }

    public function undislike(Model $object): bool
    {
        return Like::query()
            ->where('likeable_id', $object->getKey())
            ->where('likeable_type', $object->getMorphClass())
            ->where('user_id', $this->getKey())
            ->where('disliked', true)
            ->delete();
    }

    public function toggleDisLike(Model $object)
    {
        return $this->hasDisLiked($object) ? $this->undislike($object) : $this->dislike($object);
    }

    public function hasDisLiked(Model $object): bool
    {
        return ($this->relationLoaded('dislikes') ? $this->dislikes : $this->dislikes())
                ->where('likeable_id', $object->getKey())
                ->where('likeable_type', $object->getMorphClass())
                ->count() > 0;
    }

    public function dislikes(): HasMany
    {
        return $this->hasMany(Like::class, 'user_id', $this->getKeyName())
            ->where('disliked', true);
    }
}
