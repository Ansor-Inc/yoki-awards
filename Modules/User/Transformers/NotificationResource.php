<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'type' => $this->data['type'] ?? '',
            'title' => $this->data['title'] ?? '',
            'body' => $this->data['body'] ?? '',
            'created_at' => $this->created_at
        ];
    }
}
