<?php

namespace Modules\Book\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'description' => $this->whenNotNull($this->description),
            'language' => $this->whenNotNull($this->language),
            'page_count' => $this->whenNotNull($this->page_count),
            'publication_date' => $this->whenNotNull($this->publication_date),
            'is_free' => (bool)$this->is_free,
            'price' => $this->whenNotNull($this->price),
            'compare_price' => $this->whenNotNull($this->compare_price),
            'tags' => $this->whenLoaded('tags', fn() => $this->tags->pluck('name')),
            'genre' => GenreResource::make($this->whenLoaded('genre')),
            'author' => AuthorResource::make($this->whenLoaded('author')),
            'publisher' => PublisherResource::make($this->whenLoaded('publisher')),
            'book_type' => $this->whenNotNull($this->book_type),
            'fragment' => $this->whenAppended('fragment'),
            'book_file' => $this->when($this->is_free, $this->whenAppended('book_file'))
        ];
    }
}
