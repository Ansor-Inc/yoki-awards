<?php

namespace Modules\Book\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class GenreResource extends JsonResource
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
            'icon' => $this->icon,
            'icon_active' => $this->icon_active,
            'title' => $this->title
        ];
    }
}
