<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'avatar' => url($this->avatar),
            'phone' => $this->phone,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate,
            'email' => $this->email,
            'region' => $this->region,
            'registered_at' => $this->created_at->format('d.m.Y')
        ];
    }
}
