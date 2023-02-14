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
            'from_balance' => $this->from_balance,
            'state' => $this->state,
            'created_at' => $this->created_at?->format('d.m.Y'),
            'book' => BookListingResource::make($this->whenLoaded('book'))
        ];
    }
}
