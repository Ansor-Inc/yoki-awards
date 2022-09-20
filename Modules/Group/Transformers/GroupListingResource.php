<?php

namespace Modules\Group\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Group\Enums\GroupUserStatus;
use Modules\User\Http\Resources\UserResource;

class GroupListingResource extends JsonResource
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
            'category' => GroupCategoryResource::make($this->whenLoaded('category')),
            'group_status' => $this->whenNotNull($this->status),
            'members' => UserResource::collection($this->whenLoaded('members')),
            'members_count' => (int)$this->members_count,
            'is_full' => (int)$this->members_count >= (int)$this->member_limit,
            'join_status' => $this->current_user_join_status
        ];
    }
}
