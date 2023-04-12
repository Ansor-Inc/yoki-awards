<?php

namespace Modules\Content\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'image' => $this->getFirstMediaUrl('banner'),
            'title' => $this->title,
            'link' => $this->link
        ];
    }
}
