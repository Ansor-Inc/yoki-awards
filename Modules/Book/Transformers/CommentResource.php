<?php

namespace Modules\Book\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'user' => [
                'id' => $this->user->id,
                'fullname' => $this->user->fullname,
                'avatar' => $this->user->avatar
            ],
            'created_date' => $this->created_at?->toDateString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'created_at_human_readable' => $this->created_at?->diffForHumans()
        ];
    }
}
