<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $created_at
 * @property mixed $id
 * @property mixed $read_at
 */
class NotificationResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->data['type'] ?? '',
            'title' => $this->data['title'] ?? '',
            'body' => $this->data['body'] ?? '',
            'is_read' => !is_null($this->read_at),
            'created_at' => $this->created_at
        ];
    }
}
