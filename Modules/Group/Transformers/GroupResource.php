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
            'title' => $this->title,
            'degree' => $this->degree,
            'member_limit' => $this->member_limit,
            'members_count' => $this->whenCounted('members'),
            'is_full' => $this->when(isset($this->member_limit) && isset($this->members_count), $this->members_count >= $this->member_limit),
            'is_private' => is_null($this->is_private) ? null : (bool)$this->is_private,
            'status' => $this->status,
//            'is_owner' => $this->whenNotNull($this->owner_id, $this->owner_id === request()->user()->id),
            'group_category' => $this->whenLoaded('category'),
            'members' => UserResource::collection($this->whenLoaded('members')),
            'invite_link' => isset($this->invite_link) ? url($this->invite_link) : null
        ];
    }
}
