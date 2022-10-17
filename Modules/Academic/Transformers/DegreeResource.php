<?php

namespace Modules\Academic\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Entities\User;

class DegreeResource extends JsonResource
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
            'title' => $this->label(),
            'interval' => $this->intervalToDisplay(),
            'degree' => $this->value,
            'academics' => User::query()->where('degree', $this->value)->limit(3)->get(['id', 'avatar']),
            'academics_count' => User::query()->where('degree', $this->value)->count()
        ];
    }
}
