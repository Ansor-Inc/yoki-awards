<?php

namespace Modules\Book\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'image' => $this->getFirstMediaUrl('image'),
            'description' => $this->description,
            'language' => $this->language,
            'publication_date' => $this->publication_date,
            'is_free' => (bool)$this->is_free,
            'price' => $this->price,
            'compare_price' => $this->compare_price,
            'voice_director' => $this->voice_director,
            'book_type' => $this->book_type,
            'shop_link' => $this->shop_link,

            'genre' => GenreResource::make($this->whenLoaded('genre')),
            'author' => AuthorResource::make($this->whenLoaded('author')),
            'publisher' => PublisherResource::make($this->whenLoaded('publisher')),
            'tags' => $this->whenLoaded('tags', fn() => $this->tags->pluck('name')),

            'rating' => round($this->rating, 1),
            'vote_count' => $this->vote_count,
            'page_count' => $this->page_count,
            'readers_count' => $this->readers_count,
            'is_bought' => Auth::check() ? $this->isBoughtBy(auth()->user()) : false,
            'fragment' => $this->getFirstMediaUrl('fragment'),
            'book_file' => $this->getBookFileUrl(),
            'audio_files' => $this->getAudioFileUrls(),
            'user_status' => BookUserStatusResource::make($this->whenLoaded('currentUserStatus')),
            'rating_percentage' => $this->percentage_per_rating
        ];
    }
}
