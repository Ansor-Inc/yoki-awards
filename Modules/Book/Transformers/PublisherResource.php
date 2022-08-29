<?php

namespace Modules\Book\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PublisherResource extends JsonResource
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
            'logo' => $this->logo,
            'title' => $this->title,
            'description' => $this->description,
            'phone' => $this->phone,
            'address' => $this->address,
            'location_url' => $this->location_url
        ];
    }
}
