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
            'created_at' => $this->created_at?->diffForHumans(),
            'comments_count' => $this->comments_count,
            'likes_count' => $this->likes_count
        ];
    }
}
