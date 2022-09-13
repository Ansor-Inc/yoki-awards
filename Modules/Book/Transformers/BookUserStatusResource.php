<?php

namespace Modules\Book\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BookUserStatusResource extends JsonResource
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
            'rating' => $this->rating,
            'bookmarked' => (bool)$this->bookmarked
        ];
    }
}
