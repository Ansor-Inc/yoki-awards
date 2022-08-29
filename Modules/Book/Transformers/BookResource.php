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
            'description' => $this->description,
            'language' => $this->language,
            'rating' => round($this->rating, 1),
            'page_count' => $this->page_count,
            'publication_date' => $this->publication_date,
            'is_free' => (bool)$this->is_free,
            'price' => $this->price,
            'compare_price' => $this->compare_price,
            'tags' => $this->whenLoaded('tags', fn() => $this->tags->pluck('name')),
            'genre' => GenreResource::make($this->whenLoaded('genre')),
            'author' => AuthorResource::make($this->whenLoaded('author')),
            'publisher' => PublisherResource::make($this->whenLoaded('publisher')),
            'book_type' => $this->book_type,
            'fragment' => $this->fragment,
            'book_file' => $this->when($this->is_free, $this->book_file),
            'user_status' => $this->when(
                auth('sanctum')->check(),
                BookUserStatusResource::make($this->statusOf(auth('sanctum')->user()))
            ),
            'book_variants' => $this->when($request->has('withVariants'), BookVariantResource::collection($this->whenAppended('book_variants')))
        ];
    }
}
