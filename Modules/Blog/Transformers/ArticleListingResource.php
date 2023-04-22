<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ArticleListingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => Str::limit(strip_tags($this->body), 190),
            'published' => (bool)$this->published,
            'views' => (int)$this->views,
            'created_at' => $this->created_at?->toDateTimeString()
        ];
    }
}
