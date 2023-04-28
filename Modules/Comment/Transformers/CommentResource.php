<?php

namespace Modules\Comment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Reaction\Transformers\ReactionResource;

/**
 * @property mixed $userLike
 * @property mixed $user
 * @property mixed $id
 * @property mixed $body
 * @property mixed $created_at
 */
class CommentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'replies' => self::collection($this->whenLoaded('descendants')),
            'user' => [
                'id' => $this->user->id,
                'fullname' => $this->user->fullname,
                'avatar' => $this->user->avatar
            ],
            'created_date' => $this->created_at?->toDateString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'created_at_human_readable' => $this->created_at?->diffForHumans(),
            'reaction' => ReactionResource::make($this)
        ];
    }
}
