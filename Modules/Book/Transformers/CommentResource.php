<?php

namespace Modules\Book\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'replies' => self::collection($this->whenLoaded('descendants')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'created_date' => $this->created_at?->toDateString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'created_at_human_readable' => $this->created_at?->diffForHumans()
        ];
    }
}
