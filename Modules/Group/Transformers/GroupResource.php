<?php

namespace Modules\Group\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'category' => GroupCategoryResource::make($this->whenLoaded('category')),
            'degree_scope' => $this->degree_scope,
            'created_at' => $this->created_at?->format('d.m.Y'),
            'posts_count' => $this->whenCounted('posts'),
            'most_reviewed_posts' => $this->mostReviewedPosts,
            'member_limit' => $this->member_limit,
            'is_private' => is_null($this->is_private) ? null : (bool)$this->is_private,
            'status' => $this->status,
            'group_category' => $this->whenLoaded('category'),
            'invite_link' => isset($this->invite_link) ? url($this->invite_link) : null,
            'is_owner' => isset($this->owner_id) ? (int)$this->owner_id === (int)auth()->id() : null,
            'join_status' => $this->current_user_join_status,
            'current_user_permissions' => $this->current_user_permissions
        ];
    }
}
