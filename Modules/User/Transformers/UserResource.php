<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $fullname
 * @property mixed $phone_verified_at
 * @property mixed $phone
 * @property mixed $degree
 * @property mixed $gender
 * @property mixed $birthdate
 * @property mixed $email
 * @property mixed $region
 * @property mixed $created_at
 * @property mixed $roles
 */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'avatar' => $this->avatar ?? asset('media/avatardefault.png'),
            'has_verified_phone' => !is_null($this->phone_verified_at),
            'phone' => $this->phone,
            'degree' => $this->degree,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate,
            'email' => $this->email,
            'region' => $this->region,
            'registered_at' => $this->created_at?->format('d.m.Y'),
            'roles' => $this->roles->pluck('name')
        ];
    }
}
