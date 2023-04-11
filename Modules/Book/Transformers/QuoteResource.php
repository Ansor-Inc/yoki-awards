<?php

namespace Modules\Book\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'user' => [
                'avatar' => $this->user?->avatar,
                'fullname' => $this->user?->fullname
            ],
            'created_at' => $this->created_at,
            'book' => [
                'id' => $this->book?->id,
                'image' => $this->book?->image
            ]
        ];
    }
}
