<?php

namespace Modules\Book\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BookListingResource extends JsonResource
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
            'image' => $this->image,
            'is_free' => (bool)$this->is_free,
            'price' => $this->price,
            'book_type' => $this->book_type,
            'author' => AuthorResource::make($this->whenLoaded('author')),
            'publisher' => PublisherResource::make($this->whenLoaded('publisher')),
            'rating' => is_null($this->rating) ? null : round($this->rating, 1),
        ];
    }
}