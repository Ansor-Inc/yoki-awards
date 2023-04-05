<?php

namespace Modules\Post\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ActualPostResource extends JsonResource
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
            'excerpt' => Str::limit($this->body, 60),
            'group' => [
                'title' => $this->group?->title,
                'category' => $this->group?->category?->title
            ],

        ];
    }
}
