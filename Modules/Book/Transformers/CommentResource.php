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
            'replies' => self::collection($this->whenLoaded('replies')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'created_at' => $this->created_at?->toDateString()
        ];
    }
}
