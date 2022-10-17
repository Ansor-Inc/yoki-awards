<?php

namespace Modules\User\Http\Requests\Traits;

use Illuminate\Support\Str;

trait ValidatesPhoneNumber
{
    protected function prepareForValidation()
    {
        if (isset($this->phone)) {
            $this->merge([
                'phone' => Str::remove('+', $this->phone),
            ]);
        }
    }
}