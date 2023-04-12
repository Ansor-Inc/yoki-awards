<?php

namespace Modules\Content\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PopupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'code' => $this->code
        ];
    }
}
