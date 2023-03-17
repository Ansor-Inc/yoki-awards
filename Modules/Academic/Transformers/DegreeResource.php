<?php

namespace Modules\Academic\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Entities\User;

class DegreeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->label(),
            'icon' => $this->icon(),
            'interval' => $this->intervalToDisplay(),
            'degree' => $this->value,
            'academics' => User::query()->where('degree', $this->value)->limit(3)->get(['id', 'avatar']),
            'academics_count' => User::query()->where('degree', $this->value)->count()
        ];
    }
}
