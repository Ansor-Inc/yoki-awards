<?php

namespace Modules\Group\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $title
 * @property mixed $degree_scope
 * @property mixed $created_at
 * @property mixed $mostReviewedPosts
 * @property mixed $member_limit
 * @property mixed $is_private
 * @property mixed $status
 * @property mixed $invite_link
 * @property mixed $current_user_join_status
 * @property mixed $current_user_permissions
 */
class GroupResource extends JsonResource
{
    public function toArray($request): array
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
            'invite_link' => $this->invite_link,
            'is_owner' => isset($this->owner_id) ? (int)$this->owner_id === (int)auth()->id() : null,
            'join_status' => $this->current_user_join_status,
            'current_user_permissions' => $this->current_user_permissions
        ];
    }
}
