<?php

namespace Modules\Group\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'fullname' => $this->fullname,
            'role' => $this->is_admin ? 'moderator' : 'user',
            'degree' => $this->degree,
            'approved' => $this->whenPivotLoaded('memberships', fn() => (bool)$this->pivot->approved)
        ];
    }
}
