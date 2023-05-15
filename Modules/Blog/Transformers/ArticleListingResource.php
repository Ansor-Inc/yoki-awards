<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @property mixed $status
 * @property mixed $id
 * @property mixed $title
 * @property mixed $body
 * @property mixed $views
 * @property mixed $created_at
 */
class ArticleListingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => Str::limit(strip_tags($this->body), 190),
            'status' => $this->status,
            'views' => (int)$this->views,
            'created_at' => $this->created_at?->toDateTimeString()
        ];
    }
}
