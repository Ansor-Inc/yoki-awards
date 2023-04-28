<?php

namespace Modules\Reaction\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $userLike
 */
class ReactionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'has_liked' => $this->when($this->relationLoaded('userLike'), fn() => !is_null($this->userLike) && !$this->userLike->disliked),
            'has_disliked' => (bool)$this->when($this->relationLoaded('userLike'), fn() => $this->userLike?->disliked),
            'likes_count' => $this->whenCounted('likes'),
            'dislikes_count' => $this->whenCounted('dislikes'),
        ];
    }
}
