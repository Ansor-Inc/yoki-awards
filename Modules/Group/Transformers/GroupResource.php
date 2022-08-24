<?php

namespace Modules\Group\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class GroupResource extends JsonResource
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
            'title' => $this->whenNotNull($this->title),
            'degree' => $this->whenNotNull($this->degree),
            'member_limit' => $this->whenNotNull($this->member_limit),
            'members_count' => $this->whenCounted('members'),
            'is_full' => $this->when(isset($this->member_limit) && isset($this->members_count), $this->members_count >= $this->member_limit),
            'is_private' => $this->whenNotNull($this->is_private),
            'status' => $this->whenNotNull($this->status),
            'group_category' => $this->whenLoaded('category'),
            'members' => UserResource::collection($this->whenLoaded('members')),
        ];
    }
}
