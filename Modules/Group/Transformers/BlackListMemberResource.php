<?php

namespace Modules\Group\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BlackListMemberResource extends JsonResource
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
            'black_list_member_id' => $this->id,
            'user_id' => $this->user_id,
            'fullname' => $this->fullname,
            'avatar' => $this->avatar,
            'permissions' => [
                'can_comment' => (bool)$this->can_comment,
                'can_see_post' => (bool)$this->can_see_post
            ]

        ];
    }
}
