<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request): array
    {
        return array(
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'views' => (int)$this->views,
            'tags' => $this->tags->pluck('name'),
            'created_at' => $this->created_at?->toDateTimeString()
        );
    }
}
