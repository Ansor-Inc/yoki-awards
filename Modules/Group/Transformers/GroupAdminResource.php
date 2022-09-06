<?php

namespace Modules\Group\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupAdminResource extends JsonResource
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
            'group_admin_id' => $this->id,
            'user_id' => $this->user_id,
            'fullname' => $this->fullname,
            'avatar' => $this->avatar,
            'permissions' => [
                'can_update_group' => (bool)$this->can_update_group,
                'can_create_post' => (bool)$this->can_create_post,
                'can_add_to_blacklist' => (bool)$this->can_add_to_blacklist
            ]
        ];
    }
}
