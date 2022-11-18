<?php

namespace Modules\Book\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'amount' => $this->amount,
            'state' => $this->state,
            'created_at' => $this->created_at?->format('d.m.Y'),
            'book' => [
                'id' => $this->book_data['id'],
                'title' => $this->book_data['title'],
                'publisher' => isset($this->book_data['publisher']) ? [
                    'id' => $this->book_data['publisher']['id'],
                    'title' => $this->book_data['publisher']['title']
                ] : null
            ]
        ];
    }
}
