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
            'logo' => $this->whenNotNull($this->logo),
            'title' => $this->whenNotNull($this->title),
            'description' => $this->whenNotNull($this->description),
            'phone' => $this->whenNotNull($this->phone),
            'address' => $this->whenNotNull($this->address),
            'location_url' => $this->whenNotNull($this->location_url)
        ];
    }
}
