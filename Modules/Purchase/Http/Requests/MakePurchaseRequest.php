<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Http\Requests\Traits\ValidatesPhoneNumber;

class MakePurchaseRequest extends FormRequest
{
    use ValidatesPhoneNumber;

    public function rules()
    {
        return [
            'phone' => 'required|digits:12'
        ];
    }

    public function authorize()
    {
        if (is_null($this->book->price) && !$this->book->is_free) {
            return Response::deny('This book does not have price!');
        }

        return true;
    }
}