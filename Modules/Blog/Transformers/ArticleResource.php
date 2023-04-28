<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Reaction\Transformers\ReactionResource;

/**
 * @property mixed $id
 * @property mixed $title
 * @property mixed $views
 * @property mixed $created_at
 * @property mixed $tags
 * @property mixed $body
 */
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
            'created_at' => $this->created_at?->toDateTimeString(),
            'comments_count' => $this->whenCounted('comments'),
            'reaction' => ReactionResource::make($this)
        );
    }
}
