<?php

namespace Modules\Post\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,
            'image' => $this->image,
            'author' => $this->author?->fullname,
            'created_at' => $this->created_at?->format('d.m.Y'),
            'created_at_human_readable' => $this->created_at?->diffForHumans(),
            'comments_count' => $this->whenCounted('comments_count'),
            'likes_count' => $this->whenCounted('likes_count'),
            'can_edit_delete' => (int)$this->user_id === (int)auth()->id() || (int)$this->group->owner_id === (int)auth()->id()
        ];
    }
}
