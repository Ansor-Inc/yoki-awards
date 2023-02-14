<?php

namespace Modules\Group\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Transformers\UserResource;

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
            'members' => UserResource::collection($this->load(['members' => fn($query) => $query->select('users.id', 'users.avatar')->limit(3)])->members),
            'members_count' => (int)$this->members_count,
            'is_private' => (bool)$this->is_private,
            'is_full' => $this->isFull(),
            'join_status' => $this->current_user_join_status
        ];
    }
}
