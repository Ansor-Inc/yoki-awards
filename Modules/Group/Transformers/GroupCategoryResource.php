<?php

namespace Modules\Group\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'groups_count' => $this->whenCounted('groups_count')
        ];
    }
}
